<?php

namespace App\Models\Relief;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReliefSponsor extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $table = 'relief_sponsors';

    public function projectLists() {
        return $this->hasMany(ReliefProjectList::class);
    }
}
