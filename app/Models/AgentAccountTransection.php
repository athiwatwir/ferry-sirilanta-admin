<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class AgentAccountTransection
 *
 * @property string $id
 * @property string $agent_account_id
 * @property string $type
 * @property float $amount
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class AgentAccountTransection extends Model
{
    use HasUuids;
    protected $table = 'agent_account_transections';
    public $incrementing = false;

    protected $casts = [
        'amount' => 'float'
    ];

    protected $fillable = [
        'agent_account_id',
        'type',
        'amount',
        'description',
        'image_path',
        'isapproved'
    ];

    public function agentAccount()
    {
        return $this->belongsTo(AgentAccount::class, 'agent_account_id', 'id');
    }
}
