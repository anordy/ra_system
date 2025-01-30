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

    public function categoryType(){
        return $this->belongsTo(TraVehicleCategory::class, 'vehicle_category');
    }

    public function fuelType(){
        return $this->belongsTo(TraVehicleFuelType::class, 'fuel_type');
    }

    public function makeType(){
        return $this->belongsTo(TraVehicleMake::class, 'make');
    }

    public function modelType(){
        return $this->belongsTo(TraVehicleModelType::class, 'model_type', 'code');
    }

    public function modelNumber(){
        return $this->belongsTo(TraVehicleModelNumber::class, 'model_number', 'code');
    }

    public function colorType(){
        return $this->belongsTo(TraVehicleColor::class, 'vehicle_color');
    }

    public function usageType(){
        return $this->belongsTo(TraVehicleUsageType::class, 'usage_type', 'code');
    }

    public function ownerCategory(){
        return $this->belongsTo(TraVehicleOwnerCategory::class, 'owner_category', 'code');
    }

    public function transmissionType(){
        return $this->belongsTo(TraVehicleTransmissionType::class, 'transmission_type', 'code');
    }
}
