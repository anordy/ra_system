<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DlApplicationCertificate extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function application(){
        return $this->belongsTo(DlLicenseApplication::class, 'dl_license_application_id');
    }
}
