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
 * Class SubRoute
 *
 * @property string $id
 * @property string $route_id
 * @property Carbon|null $close_time
 * @property Carbon $depart_time
 * @property Carbon $arrival_time
 * @property string $origin_timezone
 * @property string $destination_timezone
 * @property int|null $seatamt
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $description
 * @property float $price
 * @property float $child_price
 * @property float $infant_price
 * @property string|null $isactive
 * @property string|null $price_strategy_id
 * @property string|null $boat_type
 * @property array|null $icon_set
 *
 * @package App\Models
 */
class SubRoute extends Model
{
    use HasUuids;
    protected $table = 'sub_routes';
    public $incrementing = false;

    protected $casts = [
        'close_time' => 'datetime',
        'depart_time' => 'datetime',
        'arrival_time' => 'datetime',
        'seatamt' => 'int',
        'price' => 'float',
        'child_price' => 'float',
        'infant_price' => 'float',
        'icon_set' => 'array'
    ];

    protected $fillable = [
        'route_id',
        'close_time',
        'depart_time',
        'arrival_time',
        'origin_timezone',
        'destination_timezone',
        'seatamt',
        'created_by',
        'updated_by',
        'description',
        'price',
        'child_price',
        'infant_price',
        'isactive',
        'price_strategy_id',
        'boat_type',
        'boat_type2',
        'icon_set',
        'type',
        'master_from',
        'master_to',
        'info_from',
        'info_to',
        'text_1',
        'text_2',
        'is_deleted',
        'deleted_at'
    ];

    protected function duration(): Attribute
    {
        return Attribute::make(
            get: function () {
                $end = Carbon::parse($this->depart_time);
                $start = Carbon::parse($this->close_time);

                // ถ้าเวลาสิ้นสุดมาก่อนเวลาเริ่ม (กรณีข้ามวัน)
                if ($end->lessThan($start)) {
                    $end->addDay();
                }

                $diff = $start->diff($end);

                return sprintf('%02d:%02d', $diff->h + ($diff->d * 24), $diff->i);
            }
        );
    }

    protected $appends = ['duration'];

    public function route()
    {
        return $this->belongsTo(Route::class)->with(['departStation', 'destStation']);
    }

    public function priceStrategy()
    {
        return $this->belongsTo(PriceStrategy::class)->with(['lines']);
    }

    public function lastSchedules()
    {
        return $this->hasMany(RouteSchedule::class, 'sub_route_id', 'id')->orderBy('created_at', 'desc')->limit(3);
    }

    public function agentSubRoutes()
    {
        return $this->hasMany(AgentSubRoute::class, 'sub_route_id', 'id');
    }
}
