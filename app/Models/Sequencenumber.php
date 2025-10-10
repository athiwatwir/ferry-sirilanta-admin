<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sequencenumber
 * 
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string|null $dateformat
 * @property string|null $prefix
 * @property int $running
 * @property int $running_digit
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $agent_id
 *
 * @package App\Models
 */
class Sequencenumber extends Model
{
	protected $table = 'sequencenumbers';

	protected $casts = [
		'running' => 'int',
		'running_digit' => 'int'
	];

	protected $fillable = [
		'name',
		'type',
		'dateformat',
		'prefix',
		'running',
		'running_digit',
		'agent_id'
	];
}
