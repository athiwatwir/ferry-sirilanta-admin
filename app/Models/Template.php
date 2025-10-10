<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class Template
 *
 * @property string $id
 * @property string|null $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Template extends Model
{
    use HasUuids;
    protected $table = 'templates';
    public $incrementing = false;

    protected $fillable = [
        'name'
    ];

    public function templateLines()
    {
        return $this->hasMany(TemplateLine::class, 'template_id', 'id');
    }
}
