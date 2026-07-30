<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

/**
 * Class Agent
 *
 * @property string $id
 * @property string $name
 * @property string $code
 * @property string|null $is_use_wallet
 * @property string|null $wallet_id
 * @property string|null $is_use_api
 * @property string|null $logo
 * @property string|null $description
 * @property string|null $public_key
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Agent extends Model
{
    use HasUuids;
    protected $table = 'agents';
    public $incrementing = false;

    public const PUBLIC_KEY_LENGTH = 200;

    protected $fillable = [
        'name',
        'code',
        'is_use_wallet',
        'wallet_id',
        'is_use_api',
        'api_key',
        'logo',
        'description',
        'isactive',
        'site_url',
        'parent_agent_id',
        'type',
        'prefix',
        'is_regular_open',
        'is_child_open',
        'is_infant_open',
        'public_key',
    ];

    protected static function booted(): void
    {
        static::creating(function (Agent $agent) {
            $agent->ensurePublicKey();
        });

        static::updating(function (Agent $agent) {
            $agent->ensurePublicKey();
        });
    }

    /**
     * สร้าง public_key ความยาว 200 ตัวถ้ายังไม่มี / ความยาวไม่ถูกต้อง
     */
    public function ensurePublicKey(): void
    {
        if (blank($this->public_key) || strlen((string) $this->public_key) !== self::PUBLIC_KEY_LENGTH) {
            $this->public_key = static::generatePublicKey();
        }
    }

    /**
     * สร้าง public_key ใหม่ความยาว 200 ตัว (A-Za-z0-9)
     */
    public static function generatePublicKey(int $length = self::PUBLIC_KEY_LENGTH): string
    {
        return Str::random($length);
    }

    /**
     * บังคับสร้าง public_key ใหม่ (เช่น rotate key)
     */
    public function regeneratePublicKey(): static
    {
        $this->public_key = static::generatePublicKey();

        return $this;
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function agentSubRoutes()
    {
        return $this->hasMany(AgentSubRoute::class, 'agent_id', 'id')
            ->select('agent_sub_routes.*')
            ->join('sub_routes', 'agent_sub_routes.sub_route_id', '=', 'sub_routes.id')
            ->join('routes', 'sub_routes.route_id', '=', 'routes.id')
            ->join('stations as depart_stations', 'routes.depart_station_id', '=', 'depart_stations.id')
            ->orderBy('depart_stations.sort', 'asc')
            ->orderBy('sub_routes.depart_time')
            ->with(['subRoute.route.departStation']);
    }

    public function activeAgentSubRoutes()
    {
        return $this->hasMany(AgentSubRoute::class, 'agent_id', 'id')->where('isactive', 'Y');
    }

    public function users()
    {
        return $this->hasMany(AgentUser::class, 'agent_id', 'id')->orderBy('fullname');
    }
}
