<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MvrOwnershipTransferReason
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|MvrOwnershipTransfer[] $mvr_ownership_transfers
 *
 * @package App\Models
 */
class MvrOwnershipTransferReason extends Model
{
    const TRANSFER_REASON_SOLD='Sale/Disposal of Vehicle';
    const TRANSFER_REASON_OTHER='Other';
	protected $table = 'mvr_o_transfer_reasons';

	protected $fillable = ['name'];

	public function ownership_transfers()
	{
		return $this->hasMany(MvrOwnershipTransfer::class,'mvr_ownership_transfer_id');
	}
}
