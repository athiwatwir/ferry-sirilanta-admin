<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class StationTag
 *
 * @property string $id
 * @property string $station_id
 * @property string $tag_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class StationTag extends Model
{
    use HasUuids;
    protected $table = 'station_tags';
    public $incrementing = false;

    protected $fillable = [
        'station_id',
        'tag_id'
    ];
}
