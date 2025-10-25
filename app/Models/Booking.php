<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class Booking
 *
 * @property string $id
 * @property Carbon $departdate
 * @property int $adult_passenger
 * @property int $child_passenger
 * @property int $infant_passenger
 * @property string|null $user_id
 * @property float $totalamt
 * @property float|null $extraamt
 * @property float $amount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $ispayment
 * @property string|null $trip_type
 * @property string|null $note
 * @property string $status
 * @property string $bookingno
 * @property string|null $book_channel
 * @property string|null $ispremiumflex
 * @property string|null $promotion_id
 * @property string|null $isconflict
 * @property int|null $amend
 * @property string|null $api_merchant_id
 * @property string|null $isemailsent
 * @property string|null $referenceno
 *
 * @package App\Models
 */
class Booking extends Model
{
    use HasUuids;
    protected $table = 'bookings';
    public $incrementing = false;

    protected $casts = [
        'departdate' => 'datetime',
        'complete_date' => 'datetime',
        'cancel_date' => 'datetime',
        'adult_passenger' => 'int',
        'child_passenger' => 'int',
        'infant_passenger' => 'int',
        'totalamt' => 'float',
        'subtotal' => 'float',
        'discount' => 'float',
        'amend' => 'int'
    ];

    protected $fillable = [
        'departdate',
        'adult_passenger',
        'child_passenger',
        'infant_passenger',
        'user_id',
        'totalamt',
        'subtotal',
        'discount',
        'ispayment',
        'trip_type',
        'note',
        'status',
        'bookingno',
        'ticketno',
        'book_channel',
        'ispremiumflex',
        'promotion_id',
        'isconflict',
        'amend',
        'isemailsent',
        'referenceno',
        'agent_id',
        'sub_agent_id',
        'complete_date',
        'cancel_date',
        'reason',
        'payment_method'
    ];

    public function agent()
    {
        return $this->hasOne(Agent::class, 'id', 'agent_id');
    }

    public function bookingCustomers()
    {
        return $this->belongsToMany(Customer::class, 'booking_customers', 'booking_id', 'customer_id')->orderByPivot('isdefault', 'ASC')->OrderBy('type', 'ASC')->withPivot('isdefault');
    }


    public function bookingSubRoutes()
    {
        return $this->belongsToMany(SubRoute::class, 'booking_sub_routes', 'booking_id', 'sub_route_id')->withPivot('type', 'traveldate', 'price', 'child_price', 'infant_price', 'id', 'ischange')->with('route');
    }
}
