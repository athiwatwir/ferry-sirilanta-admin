<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class StationUser
 *
 * @property string $id
 * @property string $station_id
 * @property string $user_id
 * @property string|null $isdefault
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class StationUser extends Model
{
    use HasUuids;
    protected $table = 'station_users';
    public $incrementing = false;

    protected $fillable = [
        'station_id',
        'user_id',
        'isdefault'
    ];
}
