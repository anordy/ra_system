<?php

namespace App\Models\ReportRegister;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class RgSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'time' => 'datetime'
    ];


    public function register() {
        return $this->belongsTo(RgRegister::class, 'rg_register_id');
    }

    public function canceller(){
        return $this->belongsTo(User::class, 'cancelled_by_id');
    }

    public function job() {
        return DB::table('jobs')->where('id', $this->job_reference)->first();
    }

}
