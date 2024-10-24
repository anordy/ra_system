<?php

namespace App\Models\Ntr;

use App\Models\Country;
use App\Models\MainRegion;
use App\Models\Taxpayer;
use App\Models\TaxRegion;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NtrBusiness extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class, 'ntr_taxpayer_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function category()
    {
        return $this->belongsTo(NtrBusinessCategory::class, 'ntr_business_category_id');
    }

    public function attachments()
    {
        return $this->hasMany(NtrBusinessAttachment::class, 'ntr_business_id');
    }

    public function contacts()
    {
        return $this->hasMany(NtrBusinessContactPerson::class, 'ntr_business_id');
    }

    public function socials()
    {
        return $this->hasMany(NtrBusinessSocialAccount::class, 'ntr_business_id');
    }

    public function generateVrn()
    {
        try {
            $vrn = '07';

            $mainRegion = MainRegion::where('prefix', MainRegion::UNG)->firstOrFail();

            $vat_category = 1;
            $value = $mainRegion->vat_local + 1;
            $attribute = 'vat_local';

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

            $this->vrn = $vrn;
            $this->save();

            return true;
        } catch (\Exception $e) {
            Log::error('NTR-BUSINESS-MODEL-GENERATE-VRN', [$e]);
            return false;
        }
    }

    public function generateZNumber()
    {
        try {
            $ztn_number = 'Z05';
            $mainRegion = MainRegion::where('prefix', MainRegion::UNG)->firstOrFail();

            // Append year
            $ztn_number = $ztn_number . Carbon::now()->format('y');

            // TODO: Confirm of what tax region is to be used?
            $taxRegion = TaxRegion::where('code', 'headquarter')->firstOrFail();

            $value = $mainRegion->registration_count + 1;

            //Append Number 000001 - 999999
            $ztn_number = $ztn_number . str_pad($value, 5, "0", STR_PAD_LEFT);

            $mainRegion->registration_count = $value;
            $mainRegion->save();
            $taxRegion->registration_count += 1;
            $taxRegion->save();

            $this->ztn_number = $ztn_number;
            $this->save();

            $this->generateLocationZNumber();

            $this->generateVrn();

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

    public function generateLocationZNumber()
    {
        try {
            // TODO: Confirm of what tax region is to be used?
            $region = TaxRegion::where('code', 'headquarter')->firstOrFail();

            $ztnLocationNumber = $region->prefix;
            $ztnLocationNumber = $ztnLocationNumber . 0;

            $this->ztn_location_number = $this->ztn_number . '-' . $ztnLocationNumber;
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
}
