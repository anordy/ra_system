<?php

namespace App\Models;

use App\Models\Tra\ChassisNumber;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class MvrRegistrationParticularChange extends Model
{
    use HasFactory, SoftDeletes, WorkflowTrait;

    protected $guarded = [];

    protected $table = 'mvr_registrations_particular_change';

    public function chassis(){
        return $this->belongsTo(ChassisNumber::class, 'chassis_number_id');
    }

    public function taxpayer(){
        return $this->belongsTo(Taxpayer::class, 'taxpayer_id');
    }

    public function agent(){
        return $this->hasOne(MvrAgent::class, 'taxpayer_id', 'taxpayer_id');
    }

    public function platecolor(){
        return $this->belongsTo(MvrPlateNumberColor::class, 'plate_number_color_id');
    }

    public function regtype(){
        return $this->belongsTo(MvrRegistrationType::class, 'mvr_registration_type_id');
    }

    public function platesize(){
        return $this->belongsTo(MvrPlateSize::class, 'mvr_plate_size_id');
    }

    public function class(){
        return $this->belongsTo(MvrClass::class, 'mvr_class_id');
    }

    public function latestBill()
    {
        return $this->morphOne(ZmBill::class, 'billable')->latest();
    }

    public function change(){
        return $this->hasOne(ChassisNumberChange::class, 'particular_change_id');
    }

    public static function getNexPlateNumber(mixed $regType, $class): mixed
    {
        $last_reg = MvrRegistrationParticularChange::query()
            ->where(['mvr_registration_type_id' => $regType->id])
            ->where(['mvr_class_id' => $class->id])
            ->whereNotNull('plate_number')
            ->orderBy('plate_number', 'desc')
            ->lockForUpdate()
            ->first();

        if ($regType->name == MvrRegistrationType::TYPE_CORPORATE){
            if (empty($last_reg)) {
                $number = str_pad( '1', 4, '0', STR_PAD_LEFT);
                $plate_number = 'SLS' . $number.$class->name;
            } else {
                $number = preg_replace('/SLS(\d{4}){}/', '$1', $last_reg->plate_number);
                $plate_number = str_pad($number + 1, 4, '0', STR_PAD_LEFT);
                $plate_number = preg_replace('/SLS(.+)(.)$/', 'SMZ' . $plate_number . '$2', $last_reg->plate_number);
            }
        }elseif ($regType->name == MvrRegistrationType::TYPE_GOVERNMENT_SLS){
            if (empty($last_reg)) {
                $number = str_pad( '1', 4, '0', STR_PAD_LEFT);
                $plate_number = 'SMZ' . $number.$class->name;
            } else {
                $number = preg_replace('/SMZ(\d{4}){}/', '$1', $last_reg->plate_number);
                $plate_number = str_pad($number + 1, 4, '0', STR_PAD_LEFT);
                $plate_number = preg_replace('/SMZ(.+)(.)$/', 'SMZ' . $plate_number . '$2', $last_reg->plate_number);
            }
        }else{
            $reg_type_ids = MvrRegistrationType::query()->whereIn('name',[
                MvrRegistrationType::TYPE_PRIVATE_PERSONALIZED,
                MvrRegistrationType::TYPE_PRIVATE_ORDINARY,
                MvrRegistrationType::TYPE_COMMERCIAL_GOODS_VEHICLE,
                MvrRegistrationType::TYPE_COMMERCIAL_PRIVATE_HIRE,
                MvrRegistrationType::TYPE_COMMERCIAL_SCHOOL_BUS,
                MvrRegistrationType::TYPE_COMMERCIAL_STAFF_BUS,
                MvrRegistrationType::TYPE_COMMERCIAL_TAXI,
            ])->pluck('id')->toArray();

            $last_reg = MvrRegistrationParticularChange::query()
                ->whereIn('mvr_registration_type_id',$reg_type_ids)
                ->whereNotNull('plate_number')
                ->orderBy(DB::raw('CONCAT(SUBSTR(plate_number,5,2),SUBSTR(plate_number,2,3))'), 'desc')
                ->lockForUpdate()
                ->first();

            if (empty($last_reg)) {
                $plate_number = $regType->initial_plate_number;
            } else {
                $number = preg_replace('/Z(\d{3})([A-Z]{2})/', '$1', $last_reg->plate_number);
                $alpha = preg_replace('/Z(\d{3})([A-Z]{2})/', '$2', $last_reg->plate_number);
                $alpha = !empty($alpha) ? $alpha : 'AA';
                if ($number == 998 || $number == 999){
                    $number = 1;
                    $alpha = preg_match('/[A-Z]Z/',$alpha) ? chr(ord(substr($alpha,0,1))+1).'A' : substr($alpha,0,1).chr(ord(substr($alpha,1,1))+1);
                }else{
                    $number++;
                }
                //check special number
                if ($number%111==0) $number++;

                $number = str_pad($number, 3, '0', STR_PAD_LEFT);
                $plate_number = 'Z'.$number.$alpha;
            }
        }
        return $plate_number;
    }


}
