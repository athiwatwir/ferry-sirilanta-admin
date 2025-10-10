<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class AgentUser
 *
 * @property string $id
 * @property string $fullname
 * @property string $email
 * @property string|null $password
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class AgentUser extends Model
{
    use HasUuids;
    protected $table = 'agent_users';
    public $incrementing = false;

    protected $hidden = [
        'password'
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    protected $fillable = [
        'agent_id',
        'fullname',
        'email',
        'password'
    ];
}
