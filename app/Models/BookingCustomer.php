<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class BookingCustomer
 *
 * @property string $id
 * @property string $booking_id
 * @property string $customer_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $isdefault
 *
 * @package App\Models
 */
class BookingCustomer extends Model
{
    use HasUuids;
    protected $table = 'booking_customers';
    public $incrementing = false;

    protected $fillable = [
        'booking_id',
        'customer_id',
        'isdefault'
    ];
}
