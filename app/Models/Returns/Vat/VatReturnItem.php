<?php

namespace App\Models\Returns\Vat;

use App\Enum\SubVatConstant;
use App\Models\Returns\Vat\SubVat;
use App\Models\Returns\Vat\VatReturn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VatReturnItem extends Model
{
    use HasFactory, SoftDeletes;

    public function getsubVatCode(){
        $vat = VatReturn::withTrashed()->findOrFail($this->return_id, ['sub_vat_id']);
        $subVat = SubVat::findOrFail((int)$vat->sub_vat_id);
        return in_array($subVat->code, [SubVatConstant::FINANCIALSERVICES, SubVatConstant::TELECOMMUNICATIONDATASERVICES, SubVatConstant::TELECOMMUNICATIONVOICESERVICES, SubVatConstant::TELEPHONE]);
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
