<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class Wallet
 *
 * @property string $id
 * @property float $balance
 * @property string|null $currency
 * @property string|null $isactive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Wallet extends Model
{
    use HasUuids;
    protected $table = 'wallets';
    public $incrementing = false;

    protected $casts = [
        'balance' => 'float'
    ];

    protected $fillable = [
        'balance',
        'currency',
        'isactive',
        'use_limit',
        'use_over_limit',
        'use_over_limit_type'
    ];

    public function agents()
    {
        return $this->hasMany(Agent::class, 'wallet_id', 'id');
    }
}
