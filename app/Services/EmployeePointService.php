<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Single source of truth for employee Point calculation.
 *
 * Point = adult_passenger × จำนวน booking_sub_routes
 * (เด็ก/ทารกไม่นับ)
 * Eligible booking: ispayment=Y, isearned=N, status not delete/void/VO/EXPIRED
 */
class EmployeePointService
{
    public const EXCLUDED_STATUSES = ['delete', 'void', 'VO', 'EXPIRED'];

    /**
     * Point ของ booking 1 รายการ
     *
     * @param  object|array  $booking  ต้องมี adult_passenger และจำนวน leg (sub_route_count / booking_sub_routes_count / relation)
     */
    public function forBooking(object|array $booking, ?int $subRouteCount = null): int
    {
        $adults = is_array($booking)
            ? (int) ($booking['adult_passenger'] ?? 0)
            : (int) ($booking->adult_passenger ?? 0);

        $legs = $subRouteCount ?? $this->resolveSubRouteCount($booking);

        return $adults * max(0, $legs);
    }

    /**
     * SQL: adult × count(booking_sub_routes) ต่อ 1 booking
     */
    public function sqlPointExpression(string $bookingTable = 'bookings'): string
    {
        return "{$bookingTable}.adult_passenger * ("
            . "select count(*) from booking_sub_routes"
            . " where booking_id = {$bookingTable}.id"
            . ')';
    }

    /**
     * SQL expression สำหรับ SUM point (ใช้กับ selectRaw)
     */
    public function sqlSumExpression(string $alias = 'point', string $bookingTable = 'bookings'): string
    {
        return 'sum(' . $this->sqlPointExpression($bookingTable) . ") as {$alias}";
    }

    /**
     * กรอง booking ที่ยังสามารถถอน Point ได้
     */
    public function applyPendingFilters(Builder $query): Builder
    {
        return $query
            ->where('ispayment', 'Y')
            ->where('isearned', 'N')
            ->whereNotIn('status', self::EXCLUDED_STATUSES);
    }

    /**
     * รวม Point ที่ยังไม่ถอน แยกตาม sales_partner_id
     *
     * @return Collection<string, int> key = sales_partner_id, value = point
     */
    public function pendingTotalsByPartnerIds(iterable $salesPartnerIds): Collection
    {
        $ids = collect($salesPartnerIds)->filter()->values();
        if ($ids->isEmpty()) {
            return collect();
        }

        return $this->applyPendingFilters(
            Booking::whereIn('sales_partner_id', $ids)
        )
            ->selectRaw('sales_partner_id, ' . $this->sqlSumExpression())
            ->groupBy('sales_partner_id')
            ->pluck('point', 'sales_partner_id')
            ->map(fn ($point) => (int) $point);
    }

    /**
     * รวม Point ที่ยังไม่ถอนของ partner คนเดียว
     */
    public function pendingTotalForPartner(string $salesPartnerId): int
    {
        return (int) $this->applyPendingFilters(
            Booking::where('sales_partner_id', $salesPartnerId)
        )->sum(DB::raw($this->sqlPointExpression()));
    }

    /**
     * รวม Point ที่ยังไม่ถอนของ user (dashboard)
     */
    public function pendingTotalForUser(string $userId, ?string $salesPartnerId = null): int
    {
        $query = Booking::where('user_id', $userId);
        if ($salesPartnerId) {
            $query->where('sales_partner_id', $salesPartnerId);
        }

        return (int) $this->applyPendingFilters($query)
            ->sum(DB::raw($this->sqlPointExpression()));
    }

    /**
     * รายการ booking ที่ยังไม่ถอน (แนบ property point ให้แต่ละรายการ)
     */
    public function pendingBookingsForPartner(string $salesPartnerId, ?string $orderBy = 'departdate'): Collection
    {
        $query = $this->applyPendingFilters(
            Booking::where('sales_partner_id', $salesPartnerId)
        )->withCount('bookingSubRoutes as sub_route_count');

        if ($orderBy) {
            $query->orderBy($orderBy, 'desc');
        }

        return $this->attachPoint($query->get());
    }

    /**
     * รายการ booking ที่ยังไม่ถอน ตามช่วงวันที่ (หน้าถอน Point)
     */
    public function pendingBookingsInDateRange(
        string $salesPartnerId,
        string $dateType,
        Carbon $startDate,
        Carbon $endDate
    ): Collection {
        $query = $this->applyPendingFilters(
            Booking::where('sales_partner_id', $salesPartnerId)
        )->withCount('bookingSubRoutes as sub_route_count');

        if ($dateType === 'booking_date') {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc');
        } else {
            $query->whereBetween('departdate', [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d'),
            ])->orderBy('departdate', 'desc');
        }

        return $this->attachPoint($query->get());
    }

    /**
     * แนบ property point ให้ทุก booking ใน collection
     */
    public function attachPoint(Collection $bookings): Collection
    {
        // เติม withCount ถ้ายังไม่มี เพื่อกัน N+1
        $needsCount = $bookings->contains(function ($booking) {
            return !isset($booking->sub_route_count)
                && !isset($booking->booking_sub_routes_count)
                && !($booking->relationLoaded('bookingSubRoutes') ?? false);
        });

        if ($needsCount && $bookings->isNotEmpty()) {
            $counts = DB::table('booking_sub_routes')
                ->whereIn('booking_id', $bookings->pluck('id'))
                ->selectRaw('booking_id, count(*) as sub_route_count')
                ->groupBy('booking_id')
                ->pluck('sub_route_count', 'booking_id');

            foreach ($bookings as $booking) {
                $booking->sub_route_count = (int) ($counts[$booking->id] ?? 0);
            }
        }

        return $bookings->map(function ($booking) {
            $booking->point = $this->forBooking($booking);
            return $booking;
        });
    }

    /**
     * รวม point จาก collection ที่แนบ point แล้ว หรือจาก booking ดิบ
     */
    public function sumPoints(Collection $bookings): int
    {
        return (int) $bookings->sum(function ($booking) {
            if (isset($booking->point)) {
                return (int) $booking->point;
            }

            return $this->forBooking($booking);
        });
    }

    /**
     * สรุปสำหรับหน้าถอน Point
     *
     * @return array{
     *   total_point: int,
     *   total_amount: float,
     *   booking_count: int,
     *   passenger_count: int
     * }
     */
    public function summarize(Collection $bookings): array
    {
        return [
            'total_point' => $this->sumPoints($bookings),
            'total_amount' => (float) $bookings->sum('totalamt'),
            'booking_count' => $bookings->count(),
            'passenger_count' => (int) $bookings->sum('adult_passenger'),
        ];
    }

    private function resolveSubRouteCount(object|array $booking): int
    {
        if (is_array($booking)) {
            if (isset($booking['sub_route_count'])) {
                return (int) $booking['sub_route_count'];
            }
            if (isset($booking['booking_sub_routes_count'])) {
                return (int) $booking['booking_sub_routes_count'];
            }

            return 0;
        }

        if (isset($booking->sub_route_count)) {
            return (int) $booking->sub_route_count;
        }
        if (isset($booking->booking_sub_routes_count)) {
            return (int) $booking->booking_sub_routes_count;
        }
        if ($booking->relationLoaded('bookingSubRoutes')) {
            return $booking->bookingSubRoutes->count();
        }

        return 0;
    }
}
