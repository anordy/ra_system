<?php

namespace App\Models;

use App\Models\BusinessStatus;
use App\Models\Returns\Vat\VatReturn;
use App\Traits\WorkflowTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Relief\Relief;
use App\Models\Returns\Petroleum\QuantityCertificate;

class Business extends Model implements Auditable
{
    use HasFactory, WorkflowTrait;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    protected $casts = [
        'date_of_commencing' => 'datetime',
    ];

    // Scopes
    public function scopeApproved($query)
    {
        $query->where('status', BusinessStatus::APPROVED);
    }

    public function scopeClosed($query)
    {
        $query->where('status', BusinessStatus::TEMP_CLOSED);
    }

    public function generateZin(){
        if ($this->zin){
            return true;
        }

        try {
            DB::beginTransaction();
            $s = 'Z';

            switch ($this->category->short_name){
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

            switch ($this->location->region->name){
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

    public function taxRegion()
    {
        return $this->belongsTo(TaxRegion::class);
    }

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class);
    }

    public function partners()
    {
        return $this->hasMany(BusinessPartner::class);
    }

    public function category()
    {
        return $this->belongsTo(BusinessCategory::class, 'business_category_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function taxTypes()
    {
        return $this->belongsToMany(TaxType::class)->withPivot('currency');
    }

    public function activityType()
    {
        return $this->belongsTo(BusinessActivity::class, 'business_activities_type_id');
    }

    public function hotel()
    {
        return $this->hasOne(BusinessHotel::class);
    }
    
    public function locations(){
        return $this->hasMany(BusinessLocation::class, 'business_id');
    }

    public function location()
    {
        return $this->hasOne(BusinessLocation::class);
    }

    public function headquarter()
    {
        return $this->hasOne(BusinessLocation::class)->where('is_headquarter', true);
    }

    public function branches()
    {
        return $this->hasMany(BusinessLocation::class)->where('is_headquarter', false);
    }

    public function bank()
    {
        return $this->hasOne(BusinessBank::class);
    }

    public function isici()
    {
        return $this->belongsTo(ISIC1::class, 'isiic_i');
    }

    public function isicii()
    {
        return $this->belongsTo(ISIC2::class, 'isiic_ii');
    }

    public function isiciii()
    {
        return $this->belongsTo(ISIC3::class, 'isiic_iii');
    }

    public function isiciv()
    {
        return $this->belongsTo(ISIC4::class, 'isiic_iv');
    }

    public function consultants()
    {
        return $this->hasMany(BusinessConsultant::class)
            ->orderByRaw("FIELD(status, 'approved', 'pending', 'rejected', 'removed')")
            ->orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc');
    }

    public function responsiblePerson()
    {
        return $this->belongsTo(Taxpayer::class, 'responsible_person_id');
    }

    public function temporaryBusinessClosures()
    {
        return $this->hasMany(TemporaryBusinessClosure::class);
    }

    public function taxTypeChanges()
    {
        return $this->hasMany(BusinessTaxTypeChange::class);
    }

    public function openBusiness()
    {
        return $this->hasOne(TemporaryBusinessClosure::class)->latest()->open();
    }

    public function businessStatus()
    {
        return $this->hasOne(BusinessStatus::class);
    }

    public function businessUpdate()
    {
        return $this->hasOne(BusinessUpdate::class);
    }
    // Files Relation
    public function files()
    {
        return $this->hasMany(BusinessFile::class);
    }

    public function vatReturn()
    {
        return $this->hasMany(VatReturn::class);
    }

    public function reliefs()
    {
        return $this->hasMany(Relief::class,'business_id');
    }

    public function waiver()
    {
        return $this->hasMany(Waiver::class);

    }

    public function objection()
    {
        return $this->hasMany(Objection::class);

    }

    public function QuantityCertificates(){
        return $this->hasMany(QuantityCertificate::class);
    }
}
