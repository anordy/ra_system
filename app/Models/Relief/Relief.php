<?php

namespace App\Models\Relief;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\User;
use OwenIt\Auditing\Contracts\Auditable;

class Relief extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;
    protected $guarded = [];

    public function projectSection()
    {
        return $this->belongsTo(ReliefProject::class,'project_id');
    }

    public function project()
    {
        return $this->belongsTo(ReliefProjectList::class, 'project_list_id');
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
