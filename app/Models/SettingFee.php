<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SettingFee
 * 
 * @property int $id
 * @property string $name
 * @property string $credit_card_fee_type
 * @property float $credit_card_fee
 * @property string $thai_qr_fee_type
 * @property float $thai_qr_fee
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $code
 *
 * @package App\Models
 */
class SettingFee extends Model
{
	protected $table = 'setting_fees';

	protected $casts = [
		'credit_card_fee' => 'float',
		'thai_qr_fee' => 'float'
	];

	protected $fillable = [
		'name',
		'credit_card_fee_type',
		'credit_card_fee',
		'thai_qr_fee_type',
		'thai_qr_fee',
		'code'
	];

	public static function feeTypes(): array
	{
		return [
			'percent' => 'Percent (%)',
			'fixed' => 'Fixed (THB)',
		];
	}

	public function formatFee(string $channel = 'credit_card'): string
	{
		$type = $channel === 'thai_qr'
			? ($this->thai_qr_fee_type ?? '')
			: ($this->credit_card_fee_type ?? '');
		$value = $channel === 'thai_qr'
			? (float) ($this->thai_qr_fee ?? 0)
			: (float) ($this->credit_card_fee ?? 0);

		if ($type === 'percent') {
			return number_format($value, 2) . '%';
		}

		return number_format($value, 2) . ' THB';
	}
}
