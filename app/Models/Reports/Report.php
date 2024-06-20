<?php

namespace App\Models\Reports;

use App\Models\Report\ReportParameter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    public function parameters() {
        return $this->hasMany(ReportParameter::class, 'report_id');
    }
}
