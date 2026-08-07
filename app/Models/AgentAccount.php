<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class AgentAccount
 *
 * @property string $id
 * @property string $sales_partner_id
 * @property string $type
 * @property float|null $credit_balance
 * @property float|null $wallet_balance
 * @property float|null $credit_limit
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class AgentAccount extends Model
{
    use HasUuids;
    protected $table = 'agent_accounts';
    public $incrementing = false;

    protected $casts = [
        'credit_balance' => 'float',
        'wallet_balance' => 'float',
        'credit_limit' => 'float'
    ];

    protected $fillable = [
        'sales_partner_id',
        'type',
        'credit_balance',
        'wallet_balance',
        'credit_limit'
    ];

    public function salesPartner()
    {
        return $this->belongsTo(SalesPartner::class, 'sales_partner_id', 'id');
    }

    public function transections()
    {
        return $this->hasMany(AgentAccountTransection::class, 'agent_account_id', 'id');
    }

    /**
     * รองรับทั้ง agent_account.id และ sales_partner_id ใน URL
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?: $this->getRouteKeyName();

        return $this->where($field, $value)->first()
            ?? static::where('sales_partner_id', $value)->first();
    }
}
