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
 * @property int|null $sort
 *
 * @package App\Models
 */
class Tag extends Model
{
    use HasUuids;
    protected $table = 'tags';
    public $incrementing = false;

    protected $casts = [
        'sort' => 'int'
    ];

    protected $fillable = [
        'name',
        'name_th',
        'sort',
        'icon_1'
    ];
}
