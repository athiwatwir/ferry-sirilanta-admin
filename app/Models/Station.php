<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


/**
 * Class Station
 *
 * @property string $id
 * @property string $section_id
 * @property string $name_en
 * @property string|null $name_th
 * @property string|null $piername_en
 * @property string|null $piername_th
 * @property string $nickname
 * @property string|null $type
 * @property int|null $sort
 * @property string|null $isactive
 * @property string|null $station_infomation_from_id
 * @property string|null $station_infomation_to_id
 * @property string|null $address
 * @property string|null $image_id
 * @property string|null $google_map
 * @property string|null $master_from
 * @property string|null $master_to
 * @property float|null $shuttle_bus_price
 * @property string|null $shuttle_bus_text
 * @property string|null $shuttle_bus_mouseover
 * @property float|null $private_taxi_price
 * @property string|null $private_taxi_text
 * @property string|null $private_taxi_mouseover
 * @property float|null $longtail_boat_price
 * @property string|null $longtail_boat_text
 * @property string|null $longtail_boat_mouseover
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Station extends Model
{

    use HasUuids;

    protected $table = 'stations';
    public $incrementing = false;


    protected $casts = [
        'sort' => 'int',
        'shuttle_bus_price' => 'float',
        'private_taxi_price' => 'float',
        'longtail_boat_price' => 'float'
    ];

    protected $fillable = [
        'section_id',
        'name_en',
        'name_th',
        'piername_en',
        'piername_th',
        'nickname',
        'type',
        'sort',
        'isactive',
        'station_infomation_from_id',
        'station_infomation_to_id',
        'address',
        'image_id',
        'google_map',
        'master_from',
        'master_to',
        'shuttle_bus_price',
        'shuttle_bus_text',
        'shuttle_bus_mouseover',
        'private_taxi_price',
        'private_taxi_text',
        'private_taxi_mouseover',
        'longtail_boat_price',
        'longtail_boat_text',
        'longtail_boat_mouseover'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
