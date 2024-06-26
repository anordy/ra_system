<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DlLicenseRestriction extends Model
{
    use HasFactory;

    protected $table = 'dl_license_restrictions';

    protected $casts = [
        'dl_license_application_id' => 'int',
        'dl_license_id' => 'int',
        'dl_restriction_id' => 'int'
    ];

    protected $fillable = [
        'dl_license_application_id',
        'dl_license_id',
        'dl_restriction_id'
    ];

    public function license_application()
    {
        return $this->belongsTo(DlLicenseApplication::class,'dl_license_application_id');
    }

    public function restriction()
    {
        return $this->belongsTo(DlRestriction::class,'dl_restriction_id');
    }

    public function license()
    {
        return $this->belongsTo(DlDriversLicense::class,'dl_license_class_id');
    }
}
