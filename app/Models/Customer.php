<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class Customer
 *
 * @property string $id
 * @property string $fullname
 * @property string|null $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $passportno
 * @property string|null $email
 * @property string|null $mobile
 * @property string|null $fulladdress
 * @property string|null $mobile_code
 * @property string|null $country
 * @property string|null $title
 * @property string|null $mobile_th
 * @property Carbon|null $birth_day
 *
 * @package App\Models
 */
class Customer extends Model
{
    use HasUuids;
    protected $table = 'customers';
    public $incrementing = false;

    protected $casts = [
        'birth_day' => 'datetime'
    ];

    protected $fillable = [
        'fullname',
        'type',
        'passportno',
        'email',
        'mobile',
        'fulladdress',
        'mobile_code',
        'country',
        'title',
        'mobile_th',
        'birth_day'
    ];
}
