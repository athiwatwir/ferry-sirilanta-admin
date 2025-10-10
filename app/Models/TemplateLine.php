<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class TemplateLine
 *
 * @property string $id
 * @property string $template_id
 * @property string|null $route_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class TemplateLine extends Model
{
    use HasUuids;
    protected $table = 'template_lines';
    public $incrementing = false;

    protected $fillable = [
        'template_id',
        'sub_route_id'
    ];

    public function subRoute()
    {
        return $this->belongsTo(SubRoute::class)->with(['route']);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}
