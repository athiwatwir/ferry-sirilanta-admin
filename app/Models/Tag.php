<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class Tag
 *
 * @property string $id
 * @property string $name
 * @property string|null $name_th
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Tag extends Model
{
    use HasUuids;
    protected $table = 'tags';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'name_th',
        'sort',
        'icon_1',
        'badge_text'
    ];

    /**
     * Stations associated via the tag_stations pivot table.
     */
    public function stations()
    {
        return $this->belongsToMany(Station::class, 'station_tags', 'tag_id', 'station_id')
            ->withPivot('id', 'sort')
            ->orderBy('station_tags.sort');
    }
}
