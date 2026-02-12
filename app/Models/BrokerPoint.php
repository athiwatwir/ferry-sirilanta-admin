<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class BrokerPoint
 *
 * @property string $id
 * @property string $sales_partner_id
 * @property int|null $balance
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class BrokerPoint extends Model
{
    use HasUuids;
    protected $table = 'broker_points';
    public $incrementing = false;

    protected $casts = [
        'balance' => 'int'
    ];

    protected $fillable = [
        'sales_partner_id',
        'balance'
    ];
}
