<?php

namespace App\Models\Ntr\Returns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NtrVatReturnAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ntr_electronic_vat_return_attachments';

    protected $guarded = [];


}
