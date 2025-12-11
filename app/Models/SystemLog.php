<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class SystemLog
 *
 * @property string $id
 * @property string $module
 * @property string $level
 * @property string $message
 * @property array|null $context
 * @property string|null $booking_id
 * @property string|null $route_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class SystemLog extends Model
{
    use HasUuids;
    protected $table = 'system_logs';
    public $incrementing = false;

    protected $casts = [
        'context' => 'json',
        'user_id' => 'int'
    ];

    protected $fillable = [
        'module',
        'level',
        'message',
        'context',
        'booking_id',
        'route_id',
        'user_id'
    ];
}
