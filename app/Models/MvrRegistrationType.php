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
    const TYPE_PRIVATE = 'Private';


    // Corporate
    const TYPE_CORPORATE = 'Corporate';


    // Government
    const TYPE_GOVERNMENT_SMZ = 'Government/SMZ';
    const TYPE_GOVERNMENT_SLS = 'Government/SLS';
    const TYPE_GOVERNMENT_INTERNATIONAL = 'Government/International';
    const TYPE_GOVERNMENT_MILITARY = 'Government/Military';
    const TYPE_GOVERNMENT_DIPLOMATIC = 'Government/Diplomatic';
    const TYPE_GOVERNMENT_DONOR_FUNDED = 'Government/Donor Funded Projects';


    // Commercial
    const TYPE_COMMERCIAL_TAXI = 'Commercial/Taxi';
    const TYPE_COMMERCIAL_PRIVATE_HIRE = 'Commercial/Private Hire';
    const TYPE_COMMERCIAL_GOODS_VEHICLE = 'Commercial/Goods Vehicle';
    const TYPE_COMMERCIAL_STAFF_BUS = 'Commercial/Staff Bus';
    const TYPE_COMMERCIAL_SCHOOL_BUS = 'Commercial/School Bus';
    const TYPE_COMMERCIAL_PUBLIC_BUS = 'Commercial/Public Bus';
    const TYPE_COMMERCIAL_TOWN_BUS = 'Commercial/Town Bus';
    const TYPE_COMMERCIAL_STAGE_BUS = 'Commercial/Stage Bus';
    const TYPE_COMMERCIAL_MOTORCYCLE = 'Commercial/Motorcycle';
    const TYPE_COMMERCIAL_TRICYCLE = 'Commercial/Tricycle';


    use SoftDeletes;

    protected $table = 'mvr_registration_types';

    protected $fillable = [
        'name',
        'initial_plate_number'
    ];

    public function color(){
        return $this->hasOne(MvrColor::class);
    }

    public function category(){
        return $this->belongsTo(MvrRegistrationTypeCategory::class, 'mvr_registration_type_category_id');
    }
}
