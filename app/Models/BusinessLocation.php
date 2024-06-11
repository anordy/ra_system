<?php

namespace App\Models;

use App\Models\Relief\Relief;
use App\Models\Returns\TaxReturn;
use App\Models\Verification\TaxVerification;
use App\Traits\WorkflowTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use App\Models\TaxType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;

class BusinessLocation extends Model implements Auditable
{
    use HasFactory, SoftDeletes, WorkflowTrait, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    protected $casts = [
        'date_of_commencing' => 'datetime',
        'effective_date' => 'datetime'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function taxRegion()
    {
        return $this->belongsTo(TaxRegion::class, 'tax_region_id');
    }


    public function generateZin()
    {
        if ($this->zin) {
            return true;
        }

        try {
            DB::beginTransaction();
            $s = 'Z';

            switch ($this->business->category->short_name) {
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

            switch ($this->region->location) {
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
            if (!$this->taxRegion) {
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
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    public function taxType()
    {
        return $this->belongsTo(TaxType::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function street()
    {
        return $this->belongsTo(Street::class);
    }

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class, 'taxpayer_id');
    }

    public function reliefs()
    {
        return $this->hasMany(Relief::class, 'location_id');
    }

    public function taxClearanceRequest()
    {
        return $this->hasMany(TaxClearanceRequest::class);
    }

    public function landLeases()
    {
        return $this->hasMany(LandLease::class, 'business_location_id');
    }

    public function taxReturns()
    {
        return $this->hasMany(TaxReturn::class, 'location_id');
    }

    public function hotel()
    {
        return $this->hasOne(BusinessHotel::class, 'location_id');
    }

    public function generateVrn()
    {

        try {

            $vrn = null;
            // Append region prefix
            switch ($this->region->location) {
                case Region::UNGUJA:
                    $vrn = $vrn . '07';
                    break;
                case Region::PEMBA:
                    $vrn = $vrn . '08';
                    break;
                default:
                    abort(404);
            }

            $mainRegion = MainRegion::where('prefix', MainRegion::UNG)->firstOrFail();

            if (!$this->business->taxTypes->where('code', 'excise-duty-mno')->isEmpty()) {
                $vat_category = 3;
                $value = $mainRegion->mno_vat + 1;
                $attribute = 'mno_vat';
            } else {
                $vat_category = 1;
                $value = $mainRegion->vat_local + 1;
                $attribute = 'vat_local';
            }

            if ($this->business->business_type == BusinessType::HOTEL) {
                $vat_category = 2;
                $value = $mainRegion->hotel_vat + 1;
                $attribute = 'hotel_vat';
            }

            $vrn = $vrn . $vat_category;

            if ($mainRegion != null) {
                $mainRegion->$attribute = $value;
                $mainRegion->save();
            } else {
                abort(404);
            }

            //Append Number 000001 - 999999
            $vrn = $vrn . str_pad($value, 5, "0", STR_PAD_LEFT);
            $vrn = $vrn . Arr::random(["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"]);


            $this->business->vrn = $vrn;
            $this->business->save();
            $this->vrn = $this->business->vrn;
            $this->save();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    public function ztnGeneration()
    {

        try {

            $ztn_number = 'Z';

            // Append region prefix
            switch ($this->region->location) {
                case Region::UNGUJA:
                    $ztn_number = $ztn_number . '05';
                    $mainRegion = MainRegion::where('prefix', MainRegion::UNG)->firstOrFail();
                    break;
                case Region::PEMBA:
                    $ztn_number = $ztn_number . '06';
                    $mainRegion = MainRegion::where('prefix', MainRegion::PMB)->firstOrFail();
                    break;
                default:
                    Log::error("Invalid Main Region selected!");
                    abort(404);
            }

            // Append year
            $ztn_number = $ztn_number . Carbon::now()->format('y');

            $taxRegion = $this->taxRegion;

            $value = $mainRegion->registration_count + 1;

            //Append Number 000001 - 999999
            $ztn_number = $ztn_number . str_pad($value, 5, "0", STR_PAD_LEFT);

            $mainRegion->registration_count = $value;
            $mainRegion->save();
            $taxRegion->registration_count += 1;
            $taxRegion->save();

            $business = $this->business;
            $business->ztn_number = $ztn_number;
            $business->save();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    public function generateZ()
    {

        try {
            $business = $this->business;

            if (!$this->taxRegion) {
                Log::error("There is no tax region");
                abort(404);
            }

            $region = $this->taxRegion;

            if ($this->business->is_business_lto) {
                $ztnLocationNumber = 02;
            } else {
                $ztnLocationNumber = $region->prefix;
            }


            $no_of_existing_branches = $business->locations->where('status', '!=', BusinessStatus::PENDING)->count();

            if ($this->is_headquarter) {
                $ztnLocationNumber = $ztnLocationNumber . 0;
                $this->ztn_location_number = 0;
            } else {
                $ztnLocationNumber = $ztnLocationNumber . $no_of_existing_branches;
                $this->ztn_location_number = $no_of_existing_branches;
                $no_of_existing_branches += 1;
            }

            $business->no_of_branches = $no_of_existing_branches;
            $business->save();

            $this->zin = $business->ztn_number . '-' . $ztnLocationNumber;
            $this->save();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    public function taxVerifications()
    {
        return $this->hasMany(TaxVerification::class, 'location_id'); // Assuming a many-to-many relationship
    }
}
