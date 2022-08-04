<?php

namespace App\Models\Relief;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relief extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function reliefProject()
    {
        return $this->belongsTo(ReliefProject::class,'project_id');
    }

    public function reliefProjectItem()
    {
        return $this->belongsTo(ReliefProject::class, 'project_list_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class,'business_id');
    }

    public function location()
    {
        return $this->belongsTo(BusinessLocation::class,'location_id');
    }

    public function reliefAttachments()
    {
        return $this->hasMany(ReliefAttachment::class,'relief_id');
    }

    public function reliefItems()
    {
        return $this->hasMany(ReliefItems::class,'relief_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }

}
