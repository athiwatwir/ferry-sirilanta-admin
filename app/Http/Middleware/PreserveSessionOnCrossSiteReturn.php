<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * 2C2P (และ payment gateway อื่น) มัก POST กลับมาแบบ cross-site
 * ทำให้ cookie session ที่ SameSite=Lax ไม่ถูกส่งมาด้วย
 * Laravel จะสร้าง session ว่างแล้ว Set-Cookie ทับตัวเดิม → ผู้ใช้ถูกเด้ง logout
 *
 * ถ้า request ไม่มี session cookie เดิม ห้ามเขียน session cookie กลับไปใน response
 * จากนั้น browser จะใช้ cookie เดิมตอน follow redirect / เปิดหน้าถัดไป (top-level GET)
 */
class PreserveSessionOnCrossSiteReturn
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!$this->shouldPreserve($request)) {
            return $response;
        }

        $sessionCookie = (string) config('session.cookie');
        if ($sessionCookie === '' || $request->cookies->has($sessionCookie)) {
            return $response;
        }

        return $this->withoutSessionCookie($response, $sessionCookie);
    }

    private function shouldPreserve(Request $request): bool
    {
        return $request->is('payment/2c2p/frontend');
    }

    private function withoutSessionCookie(Response $response, string $sessionCookie): Response
    {
        $kept = [];
        foreach ($response->headers->getCookies() as $cookie) {
            if ($cookie->getName() === $sessionCookie) {
                continue;
            }
            $kept[] = $cookie;
        }

        $response->headers->remove('Set-Cookie');

        foreach ($kept as $cookie) {
            $response->headers->setCookie($cookie);
        }

        // กัน cookie ที่ queue ผ่าน cookie jar (ชื่อ session)
        foreach ($this->queuedCookies() as $cookie) {
            if ($cookie->getName() === $sessionCookie) {
                cookie()->unqueue($sessionCookie);
            }
        }

        return $response;
    }

    /**
     * @return array<int, Cookie>
     */
    private function queuedCookies(): array
    {
        try {
            return cookie()->getQueuedCookies();
        } catch (\Throwable) {
            return [];
        }
    }
}
