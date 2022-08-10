<?php

namespace App\Models;

use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objection extends Model
{
    use HasFactory ,WorkflowTrait;

    protected $guarded = [];

        public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

       public function bill()
    {
        return $this->morphOne(ZmBill::class, 'billable');
    }
        public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class);
    }

}
