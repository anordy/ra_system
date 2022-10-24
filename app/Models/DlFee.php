<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class DlFee
 * 
 * @property int $id
 * @property string $name
 * @property float $amount
 * @property string $type
 * @property string $gfs_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class DlFee extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;

	protected $table = 'dl_fees';

	protected $casts = [
		'amount' => 'float'
	];

	protected $fillable = [
		'name',
		'amount',
		'type',
		'gfs_code',
		'dl_license_duration_id',
	];


    public function license_duration()
    {
        return $this->belongsTo(DlLicenseDuration::class,'dl_license_duration_id');
    }
}
