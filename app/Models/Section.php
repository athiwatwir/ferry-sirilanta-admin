<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class Section
 *
 * @property string $id
 * @property string $name
 * @property string|null $isactive
 * @property int|null $sort
 * @property string|null $sectionscol
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Section extends Model
{
    use HasUuids;

    protected $table = 'sections';
    public $incrementing = false;

    protected $casts = [
        'sort' => 'int'
    ];

    protected $fillable = [
        'name',
        'name_th',
        'isactive',
        'sort',
        'sectionscol'
    ];

    public function stations()
    {
        return $this->hasMany(Station::class, 'section_id', 'id')->orderBy('sort', 'ASC');
    }
}
