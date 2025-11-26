<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Class SettingFee
 *
 * @property string $id
 * @property string|null $is_use_pf
 * @property string|null $is_use_sc
 * @property string $agent_id
 * @property string|null $pf_type
 * @property string|null $pf_mode
 * @property float|null $pf_regular_value
 * @property string|null $sc_type
 * @property string|null $sc_mode
 * @property float|null $sc_regular_value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $description
 *
 * @package App\Models
 */
class SettingFee extends Model
{
    use HasUuids;
    protected $table = 'setting_fees';
    public $incrementing = false;

    protected $casts = [
        'pf_regular_value' => 'float',
        'sc_regular_value' => 'float'
    ];

    protected $fillable = [
        'is_use_pf',
        'is_use_sc',
        'agent_id',
        'pf_type',
        'pf_mode',
        'pf_regular_value',
        'sc_type',
        'sc_mode',
        'sc_regular_value',
        'description'
    ];
}
