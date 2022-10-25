<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class MvrTransferFee
 * 
 * @property int $id
 * @property string $name
 * @property float $amount
 * @property string $gfs_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class MvrTransferFee extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;

	protected $table = 'mvr_transfer_fees';

	protected $casts = [
		'amount' => 'float'
	];

	protected $fillable = [
		'name',
		'amount',
		'gfs_code',
		'mvr_transfer_category_id'
	];


    public function transfer_category()
    {
        return $this->belongsTo(MvrTransferCategory::class,'mvr_transfer_category_id');
    }
}
