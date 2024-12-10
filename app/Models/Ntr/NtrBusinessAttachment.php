<?php

namespace App\Models\Ntr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NtrBusinessAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function type() {
        return $this->belongsTo(NtrBusinessAttachmentType::class, 'ntr_business_attachment_id');
    }
}
