<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class Route
 *
 * @property string $id
 * @property string $depart_station_id
 * @property string $dest_station_id
 * @property string|null $isactive
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 *
 * @package App\Models
 */
class Route extends Model
{
    use HasUuids;
    protected $table = 'routes';
    public $incrementing = false;

    protected $fillable = [
        'depart_station_id',
        'dest_station_id',
        'isactive',
        'description',
        'created_by',
        'updated_by'
    ];

    public function departStation()
    {
        return $this->hasOne(Station::class, 'id', 'depart_station_id');
    }

    public function destStation()
    {
        return $this->hasOne(Station::class, 'id', 'dest_station_id');
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function updatedBy()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    public function subRoutes()
    {
        return $this->hasMany(SubRoute::class, 'route_id', 'id')->orderBy('type', 'DESC')->orderBy('depart_time')->orderBy('arrival_time');
    }
}
