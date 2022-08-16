<?php

namespace App\Models;

use App\Models\Relief\Relief;
use App\Traits\WorkflowTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BusinessLocation extends Model
{
    use HasFactory, SoftDeletes,WorkflowTrait;

    protected $guarded = [];

    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function taxRegion()
    {
        return $this->belongsTo(TaxRegion::class);
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

            switch ($this->region->name){
                case 'Unguja':
                    $s = $s . '1';
                    break;
                case 'Pemba':
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
}
