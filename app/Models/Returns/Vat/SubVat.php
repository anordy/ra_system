<?php

namespace App\Models\Returns\Vat;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubVat extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name', 'gfs_code', 'is_approved', 'is_updated'
    ];

    public const TELECOMMUNICATIONDATASERVICES = 'VAT on  Telecommunication data services';
    public const TELECOMMUNICATIONVOICESERVICES = 'VAT on  Telecommunication Voice services';
    public const FINANCIALSERVICES = 'VAT on Financial Services';
    public const TELEPHONE = 'VAT on Telephone';
    public const INSURANCE = 'VAT on Insurance';

}
