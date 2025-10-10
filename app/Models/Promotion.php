<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class Promotion
 *
 * @property string $id
 * @property string $code
 * @property float $discount
 * @property string $discount_type
 * @property string|null $trip_type
 * @property Carbon|null $depart_date_start
 * @property Carbon|null $depart_date_end
 * @property Carbon|null $booking_start_date
 * @property Carbon|null $booking_end_date
 * @property string|null $station_from_id
 * @property string|null $station_to_id
 * @property string|null $route_id
 * @property int|null $max_use
 * @property string $title
 * @property string|null $isactive
 * @property string|null $image_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $times_use_max
 * @property string|null $isfreecreditcharge
 * @property string|null $isfreepremiumflex
 * @property string|null $isfreelongtailboat
 * @property string|null $isfreeshuttlebus
 * @property string|null $isfreeprivatetaxi
 * @property string|null $description
 *
 * @package App\Models
 */
class Promotion extends Model
{
    use HasUuids;
    protected $table = 'promotions';
    public $incrementing = false;

    protected $casts = [
        'discount' => 'float',
        'depart_date_start' => 'datetime',
        'depart_date_end' => 'datetime',
        'booking_start_date' => 'datetime',
        'booking_end_date' => 'datetime',
        'max_use' => 'int',
        'times_use_max' => 'int'
    ];

    protected $fillable = [
        'code',
        'discount',
        'discount_type',
        'trip_type',
        'startdate',
        'enddate',
        'station_from_id',
        'station_to_id',
        'route_id',
        'max_use',
        'title',
        'isactive',
        'image_id',
        'times_use_max',
        'isfreecreditcharge',
        'isfreepremiumflex',
        'isfreelongtailboat',
        'isfreeshuttlebus',
        'isfreeprivatetaxi',
        'description'
    ];
}
