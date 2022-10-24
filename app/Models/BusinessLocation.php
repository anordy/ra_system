<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Ward;
use App\Models\Region;
use App\Models\Business;
use App\Models\District;
use App\Models\Taxpayer;
use App\Models\LandLease;
use App\Models\TaxRegion;
use App\Models\BusinessHotel;
use App\Models\Relief\Relief;
use App\Traits\WorkflowTrait;
use App\Models\BusinessCategory;
use App\Models\Returns\TaxReturn;
use Illuminate\Support\Facades\DB;
use App\Models\TaxClearanceRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class BusinessLocation extends Model implements Auditable
{
    use HasFactory, SoftDeletes,WorkflowTrait, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    protected $casts = [
        'date_of_commencing' => 'datetime',
    ];

    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function taxRegion()
    {
        return $this->belongsTo(TaxRegion::class, 'tax_region_id');
    }

    public function generateZin(){
        if ($this->zin){
            return true;
        }

        try {
            DB::beginTransaction();
            $s = 'Z';

            switch ($this->business->category->short_name){
                case BusinessCategory::SOLE:
                    $s = $s . 'S';
                    break;
                case BusinessCategory::COMPANY:
                    $s = $s . 'C';
                    break;
                case BusinessCategory::PARTNERSHIP:
                    $s = $s . 'P';
                    break;
                case BusinessCategory::NGO:
                    $s = $s . 'N';
                    break;
                default:
                    abort(404);
            }

            switch ($this->region->location){
                case Region::UNGUJA:
                    $s = $s . '1';
                    break;
                case Region::PEMBA:
                    $s = $s . '2';
                    break;
                default:
                    abort(404);
            }

            // Append tax region
            if (!$this->taxRegion){
                abort(404);
            }

            $region = $this->taxRegion;

            $s = $s . $region->prefix;

            // Append random no from table
            $s = $s . sprintf("%04s", $region->registration_count + 1);

            // Append year
            $s = $s . Carbon::now()->format('y');

            // Save no, update count
            $this->zin = $s;
            $this->save();

            $region->registration_count = $region->registration_count + 1;
            $region->save();
            DB::commit();
            return true;
        } catch (\Exception $e){
            DB::rollBack();
            Log::error($e);
            return false;
        }
    }


    public function region(){
        return $this->belongsTo(Region::class);
    }

    public function district(){
        return $this->belongsTo(District::class);
    }

    public function ward(){
        return $this->belongsTo(Ward::class);
    }

    public function taxpayer(){
        return $this->belongsTo(Taxpayer::class, 'taxpayer_id');
    }

    public function reliefs()
    {
        return $this->hasMany(Relief::class,'location_id');
    }

    public function taxClearanceRequest(){
        return $this->hasMany(TaxClearanceRequest::class);
    }

    public function landLeases()
    {
        return $this->hasMany(LandLease::class, 'business_location_id');
    }

    public function taxReturns(){
        return $this->hasMany(TaxReturn::class, 'location_id');
    }

    public function hotel()
    {
        return $this->hasOne(BusinessHotel::class, 'location_id');
    }
}
