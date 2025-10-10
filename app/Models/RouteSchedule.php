<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RouteSchedule
 *
 * @property string $id
 * @property string $isopen
 * @property array|null $day_of_week
 * @property Carbon|null $startdate
 * @property Carbon|null $enddate
 * @property string $sub_route_id
 * @property string|null $isactive
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $isprocessing
 *
 * @package App\Models
 */
class RouteSchedule extends Model
{
    protected $table = 'route_schedules';
    public $incrementing = false;

    protected $casts = [
        'day_of_week' => 'json',
        'startdate' => 'datetime',
        'enddate' => 'datetime'
    ];

    protected $fillable = [
        'isopen',
        'day_of_week',
        'startdate',
        'enddate',
        'sub_route_id',
        'isactive',
        'description',
        'created_by',
        'updated_by',
        'isprocessing'
    ];

    public function subRoute()
    {
        return $this->belongsTo(SubRoute::class)->with('route');
    }
}
