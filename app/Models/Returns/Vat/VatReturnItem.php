<?php

namespace App\Models\Returns\Vat;

use App\Enum\SubVatConstant;
use App\Models\TaxType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VatReturnItem extends Model
{
    use HasFactory, SoftDeletes;

    public function getsubVatCode(){
        $vat = TaxType::where('code', TaxType::VAT)->select('id')->firstOrFail();
        $taxType = VatReturn::where('vat_returns.id', $this->return_id)
            ->leftJoin('businesses', 'businesses.id', '=', 'vat_returns.business_id')
            ->leftJoin('business_tax_type', 'business_tax_type.business_id', '=', 'businesses.id')
            ->leftJoin('sub_vats', 'sub_vats.id', '=', 'business_tax_type.sub_vat_id')
            ->where('business_tax_type.tax_type_id', $vat->id)
            ->select('sub_vats.code')
            ->first();
        return in_array($taxType->code, [SubVatConstant::FINANCIALSERVICES, SubVatConstant::TELECOMMUNICATIONDATASERVICES, SubVatConstant::TELECOMMUNICATIONVOICESERVICES, SubVatConstant::TELEPHONE]);
    }

    public function config()
    {
        if ($this->getsubVatCode()){
            return $this->belongsTo(Vat18ReturnConfig::class,'config_id','id');
        } else {
            return $this->belongsTo(VatReturnConfig::class,'config_id','id');
        }
    }
}
