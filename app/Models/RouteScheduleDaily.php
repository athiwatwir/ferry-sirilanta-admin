<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class RouteScheduleDaily
 *
 * @property string $id
 * @property string $sub_route_id
 * @property string|null $agent_id
 * @property Carbon $date
 * @property string|null $isopen
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class RouteScheduleDaily extends Model
{
    use HasUuids;
    protected $table = 'route_schedule_dailies';
    public $incrementing = false;

    protected $casts = [
        'date' => 'datetime'
    ];

    protected $fillable = [
        'sub_route_id',
        'route_schedule_id',
        'agent_id',
        'date',
        'isopen'
    ];
}
