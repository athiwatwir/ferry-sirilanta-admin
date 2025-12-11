<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class Payment
 *
 * @property string $id
 * @property string $payment_method
 * @property float $totalamt
 * @property array|null $confirm_document
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $description
 * @property string|null $booking_id
 * @property string|null $user_id
 * @property Carbon $docdate
 * @property string $paymentno
 * @property string|null $ispaid
 * @property Carbon|null $payment_date
 * @property string $status
 * @property string|null $c_accountno
 * @property string|null $c_paymentid
 * @property string|null $c_merchanid
 * @property string|null $c_invoiceno
 * @property float|null $c_amount
 * @property string|null $c_currencycode
 * @property string|null $c_tranref
 * @property string|null $c_referenceno
 * @property string|null $c_approvalcode
 * @property Carbon|null $c_datetime
 * @property string|null $c_agent
 * @property string|null $c_issuercountry
 * @property string|null $c_issuerbank
 * @property string|null $c_cardtype
 * @property float|null $amount
 * @property float|null $discount
 *
 * @package App\Models
 */
class Payment extends Model
{
    use HasUuids;
    protected $table = 'payments';
    public $incrementing = false;

    protected $casts = [
        'totalamt' => 'float',
        'confirm_document' => 'json',
        'docdate' => 'datetime',
        'payment_date' => 'datetime',
        'c_amount' => 'float',
        'c_datetime' => 'datetime',
        'amount' => 'float',
        'discount' => 'float',
        'p_feeamt' => 'float',
        's_feeamt' => 'float'
    ];

    protected $fillable = [
        'payment_method',
        'totalamt',
        'confirm_document',
        'description',
        'booking_id',
        'user_id',
        'docdate',
        'paymentno',
        'ispaid',
        'payment_date',
        'status',
        'c_accountno',
        'c_paymentid',
        'c_merchanid',
        'c_invoiceno',
        'c_amount',
        'c_currencycode',
        'c_tranref',
        'c_referenceno',
        'c_approvalcode',
        'c_datetime',
        'c_agent',
        'c_issuercountry',
        'c_issuerbank',
        'c_cardtype',
        'amount',
        'discount',
        'p_feeamt',
        's_feeamt'
    ];
}
