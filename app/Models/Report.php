<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    public function parameters() {
        return $this->hasMany(ReportParameter::class, 'report_id');
    }

    public function report_type(){
        return $this->belongsTo(ReportType::class);
    }
}
