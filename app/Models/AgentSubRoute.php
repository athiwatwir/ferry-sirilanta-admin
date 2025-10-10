<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AgentSubRoute
 *
 * @property string $id
 * @property string $agent_id
 * @property string $sub_route_id
 * @property string|null $pricing_strategy
 * @property float|null $custom_regular_price
 * @property float|null $custom_child_price
 * @property float|null $custom_infant_price
 * @property string|null $discount_type
 * @property float|null $discount_value
 * @property string|null $isactive
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class AgentSubRoute extends Model
{
    protected $table = 'agent_sub_routes';
    public $incrementing = false;

    protected $casts = [
        'discount_regular_price' => 'float',
        'discount_child_price' => 'float',
        'discount_infant_price' => 'float',
        'discount_value' => 'float',
        'price' => 'float',
        'child_price' => 'float',
        'infant_price' => 'float',
    ];

    protected $fillable = [
        'agent_id',
        'route_id',
        'sub_route_id',
        'pricing_strategy',
        'discount_regular_price',
        'discount_child_price',
        'discount_infant_price',
        'discount_type',
        'discount_value',
        'isactive',
        'price',
        'child_price',
        'infant_price',
        'isadded'
    ];

    public function agent()
    {
        return $this->hasOne(SubRoute::class, 'id', 'agent_id');
    }

    public function subRoute()
    {
        return $this->hasOne(SubRoute::class, 'id', 'sub_route_id');
    }
}
