<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
        'site_url'

    ];

    /*
    protected function activeRouteAmt(): Attribute
    {
        return Attribute::make(
            get: function () {
                self::agentSubRoutes()

                return sprintf('%02d:%02d', $diff->h + ($diff->d * 24), $diff->i);
            }
        );
    }
        */


    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function agentSubRoutes()
    {
        return $this->hasMany(AgentSubRoute::class, 'agent_id', 'id')
            ->select('agent_sub_routes.*')
            ->join('sub_routes', 'agent_sub_routes.sub_route_id', '=', 'sub_routes.id')
            ->orderBy('agent_sub_routes.route_id')
            ->orderBy('sub_routes.depart_time')
            ->with('subRoute');
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
