<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class PriceStrategyLine
 *
 * @property string $id
 * @property string $price_strategy_id
 * @property int $unit
 * @property string $condition
 * @property float $add_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $description
 *
 * @package App\Models
 */
class PriceStrategyLine extends Model
{
    use HasUuids;
    protected $table = 'price_strategy_lines';
    public $incrementing = false;

    protected $casts = [
        'unit' => 'int',
        'price' => 'float',
        'child_price' => 'float',
        'infant_price' => 'float'
    ];

    protected $fillable = [
        'price_strategy_id',
        'unit',
        'condition',
        'price',
        'child_price',
        'infant_price',
        'description'
    ];
}
