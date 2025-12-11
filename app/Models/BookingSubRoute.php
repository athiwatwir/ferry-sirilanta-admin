<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class BookingSubRoute
 *
 * @property string $id
 * @property string $sub_route_id
 * @property string|null $type
 * @property Carbon|null $traveldate
 * @property float|null $amount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $booking_id
 * @property string|null $ischange
 *
 * @package App\Models
 */
class BookingSubRoute extends Model
{
    use HasUuids;
    protected $table = 'booking_sub_routes';
    public $incrementing = false;

    protected $casts = [
        'traveldate' => 'datetime',
        'price' => 'float',
        'child_price' => 'float',
        'infant_price' => 'float'
    ];

    protected $fillable = [
        'sub_route_id',
        'type',
        'traveldate',
        'price',
        'child_price',
        'infant_price',
        'booking_id',
        'ischange',
        'ticketno'
    ];
}
