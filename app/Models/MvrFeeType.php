<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class MvrFeeType
 * 
 * @property int $id
 * @property string $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|MvrFee[] $mvr_fees
 *
 * @package App\Models
 */
class MvrFeeType extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    const TYPE_REGISTRATION = 'Registration';
    const STATUS_CHANGE = 'Status Change';
    const TRANSFER_OWNERSHIP = 'Transfer Ownership';
    const TYPE_DE_REGISTRATION = 'De-Registration';
    const TYPE_CHANGE_REGISTRATION = 'Change of Registration Particulars';

    protected $table = 'mvr_fee_types';

    protected $fillable = [
		'type'
	];

    public function mvr_fees()
	{
		return $this->hasMany(MvrFee::class);
	}
}
