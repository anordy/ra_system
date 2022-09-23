<?php

namespace App\Models\Extension;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExtensionRequestFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function extensionRequest(){
        return $this->belongsTo(ExtensionRequest::class);
    }
}
