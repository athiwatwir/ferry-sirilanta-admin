<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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
}
