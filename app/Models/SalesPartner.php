<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class SalesPartner
 *
 * @property string $id
 * @property string $name
 * @property string|null $code
 * @property string $type
 * @property string|null $isactive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $agent_id
 *
 * @package App\Models
 */
class SalesPartner extends Model
{
    use HasUuids;
    protected $table = 'sales_partners';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'code',
        'type',
        'isactive',
        'agent_id',
        'discount'
    ];

    public function brokerPoint()
    {
        return $this->hasOne(BrokerPoint::class, 'sales_partner_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'sales_partner_id', 'id')->where('isdefault', 'Y')->orderBy('created_at', 'ASC');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'sales_partner_id', 'id');
    }

    public function agentAccount()
    {
        return $this->hasOne(AgentAccount::class, 'sales_partner_id', 'id');
    }
}
