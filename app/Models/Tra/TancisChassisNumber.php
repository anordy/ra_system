<?php

namespace App\Models\Tra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TancisChassisNumber extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $table = 'tancis_chassis_numbers';

    public function categoryTypeTra(){
        return $this->belongsTo(TraVehicleCategory::class, 'vehicle_category', 'code');
    }

    public function fuelTypeTra(){
        return $this->belongsTo(TraVehicleFuelType::class, 'fuel_type', 'code');
    }

    public function makeTypeTra(){
        return $this->belongsTo(TraVehicleMake::class, 'make', 'code');
    }

    public function modelTypeTra(){
        return $this->belongsTo(TraVehicleModelType::class, 'model_type', 'code');
    }

    public function modelNumberTra(){
        return $this->belongsTo(TraVehicleModelNumber::class, 'model_number', 'code');
    }

    public function colorTypeTra(){
        return $this->belongsTo(TraVehicleColor::class, 'vehicle_color', 'code');
    }

    public function usageTypeTra(){
        return $this->belongsTo(TraVehicleUsageType::class, 'usage_type', 'code');
    }

    public function ownerCategoryTra(){
        return $this->belongsTo(TraVehicleOwnerCategory::class, 'owner_category', 'code');
    }

    public function transmissionTypeTra(){
        return $this->belongsTo(TraVehicleTransmissionType::class, 'transmission_type', 'code');
    }

    public function bodyTypeTra(){
        return $this->belongsTo(TraVehicleBodyType::class, 'body_type', 'code');
    }
}
