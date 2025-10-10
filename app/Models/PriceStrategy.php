<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class PriceStrategy
 *
 * @property string $id
 * @property string $name
 * @property string $calculate_type
 * @property string $method
 * @property string|null $isactive
 * @property Carbon|null $created_at
 * @property Carbon|null $update_at
 * @property string|null $description
 *
 * @package App\Models
 */
class PriceStrategy extends Model
{
    use HasUuids;
    protected $table = 'price_strategies';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'update_at' => 'datetime'
    ];

    protected $fillable = [
        'name',
        'calculate_type',
        'method',
        'isactive',
        'update_at',
        'description',
        'ismaster'
    ];

    public function lines()
    {
        return $this->hasMany(PriceStrategyLine::class, 'price_strategy_id', 'id')->orderBy('unit', 'ASC');
    }
}
