<?php

namespace App\Models\Returns\Vat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatWithheldAttachment extends Model
{
    use HasFactory;
    protected $table = 'vat_withheld_attachments';
    protected $guarded = [];
}
