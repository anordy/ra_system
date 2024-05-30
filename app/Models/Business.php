<?php

namespace App\Models;

use App\Models\Disputes\Objection;
use App\Models\Disputes\Waiver;
use App\Models\PublicService\PublicServiceMotor;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Tra\Tin;
use App\Traits\WorkflowTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Relief\Relief;
use App\Models\Returns\Petroleum\QuantityCertificate;

class Business extends Model implements Auditable
{
    use HasFactory, WorkflowTrait, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    // Scopes
    public function scopeApproved($query){
        $query->where('status', BusinessStatus::APPROVED);
    }

    public function scopePending($query){
        $query->where('status', BusinessStatus::PENDING);
    }

    public function scopeDraft($query){
        $query->where('status', BusinessStatus::DRAFT);
    }

    public function scopeCorrection($query){
        $query->where('status', BusinessStatus::CORRECTION);
    }

    public function scopeClosed($query){
        $query->where('status', BusinessStatus::TEMP_CLOSED);
    }

    public function taxpayer(){
        return $this->belongsTo(Taxpayer::class);
    }

    public function partners(){
        return $this->hasMany(BusinessPartner::class);
    }

    public function assistants(){
        return $this->hasMany(BusinessAssistant::class);
    }

    public function category(){
        return $this->belongsTo(BusinessCategory::class, 'business_category_id');
    }

    public function currency(){
        return $this->belongsTo(Currency::class);
    }

    public function taxTypes(){
        return $this->belongsToMany(TaxType::class)->withPivot('currency', 'sub_vat_id');
    }

    public function activityType(){
        return $this->belongsTo(BusinessActivity::class, 'business_activities_type_id');
    }

    public function hotel()
    {
        return $this->hasOne(BusinessHotel::class);
    }
    
    public function locations(){
        return $this->hasMany(BusinessLocation::class, 'business_id');
    }

    public function headquarter(){
        return $this->hasOne(BusinessLocation::class)->where('is_headquarter', true);
    }

    public function branches(){
        return $this->hasMany(BusinessLocation::class)->where('is_headquarter', false);
    }

    public function banks(){
        return $this->hasMany(BusinessBank::class);
    }

    public function isici(){
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
            ->orderByRaw("DECODE (status, 'approved', 'pending', 'rejected', 'removed')")
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

    public function taxTypeChanges(){
        return $this->hasMany(BusinessTaxTypeChange::class);
    }

    public function openBusiness(){
        return $this->hasOne(TemporaryBusinessClosure::class)->latest()->open();
    }

    public function businessStatus(){
        return $this->hasOne(BusinessStatus::class);
    }

    public function businessUpdate(){
        return $this->hasOne(BusinessUpdate::class);
    }

    // Files Relation
    public function files(){
        return $this->hasMany(BusinessFile::class);
    }

    public function vatReturn(){
        return $this->hasMany(VatReturn::class);
    }

    public function reliefs(){
        return $this->hasMany(Relief::class,'business_id');
    }

    public function waiver(){
        return $this->hasMany(Waiver::class);
    }

    public function objection(){
        return $this->hasMany(Objection::class);
    }

    public function dispute(){
        return $this->hasMany(Waiver::class);
    }

    public function QuantityCertificates(){
        return $this->hasMany(QuantityCertificate::class);
    }

    public function taxClearanceRequest(){
        return $this->hasMany(TaxClearanceRequest::class);
    }

    public function businessLocationIDs(){
        $locationIds = [];
        foreach ($this->locations as $location) {
            array_push($locationIds, $location->id);
        }
        return $locationIds;
    }

    public function businessWardName()
    {
        if ($this->locations->first() && $this->locations->first()->ward){
            return $this->locations->first()->ward->name;
        }
        return null;
    }

    public function businessStreetName()
    {
        if ($this->locations->first() && $this->locations->first()->street){
            return $this->locations->first()->street->name;
        }
        return null;
    }

    public function motors(){
        return $this->hasMany(PublicServiceMotor::class, 'business_id');
    }

    public function lumpsumPayment() {
        return $this->hasOne(LumpSumPayment::class, 'business_id');
    }
}
