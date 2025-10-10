<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class Information
 *
 * @property string $id
 * @property string $agent_id
 * @property string $title
 * @property string $body
 * @property string|null $position
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Information extends Model
{
    use HasUuids;
    protected $table = 'informations';
    public $incrementing = false;

    protected $fillable = [
        'agent_id',
        'title',
        'body',
        'position'
    ];
}
