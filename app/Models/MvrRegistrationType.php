<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MvrRegistrationType
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class MvrRegistrationType extends Model
{
    const TYPE_PRIVATE_PERSONALIZED = 'Personalized Registration';
    const TYPE_PRIVATE_GOLDEN = 'Golden Number Registration';
    const TYPE_PRIVATE_ORDINARY = 'Ordinary Registration';
    const TYPE_GOVERNMENT = 'Government (SMZ)';
    const TYPE_DIPLOMATIC = 'Diplomat';
    const TYPE_CORPORATE= 'Corporate';
    const TYPE_COMMERCIAL_TAXI= 'Commercial/Taxi';
    const TYPE_COMMERCIAL_PRIVATE_HIRE= 'Commercial/Private Hire';
    const TYPE_COMMERCIAL_GOODS_VEHICLE = 'Commercial/Goods Vehicle';
    const TYPE_COMMERCIAL_STAFF_BUS= 'Commercial/Staff Bus';
    const TYPE_COMMERCIAL_SCHOOL_BUS= 'Commercial/School Bus';
    const TYPE_DONOR_FUNDED = 'Donor Funded Project';
    const TYPE_MILITARY = 'Military';

    use SoftDeletes;

	protected $table = 'mvr_registration_types';

	protected $fillable = [
		'name',
        'initial_plate_number'
	];
}
