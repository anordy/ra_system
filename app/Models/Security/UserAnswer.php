<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAnswer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'security_question_answers';

    protected $guarded = [];

    public function question(){
        return $this->belongsTo(Question::class);
    }

    public function userable(){
        return $this->morphTo();
    }

    public function scopeApproved($query){
        return $query->where('is_approved', true);
    }

    public function scopeEdited($query){
        return $query->where('is_edited', 'true');
    }
}
