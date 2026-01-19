<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
/**
 * Class InfoImage
 *
 * @property string $id
 * @property string $name
 * @property string|null $description
 * @property string $image_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $type
 *
 * @package App\Models
 */
class InfoImage extends Model
{

    use HasUuids;
	protected $table = 'info_images';
	public $incrementing = false;

	protected $fillable = [
		'name',
		'description',
		'image_path',
		'type'
	];
}
