<?php

namespace App\Models\Tra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tin extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getFullNameAttribute(){
        return "{$this->first_name} {$this->middle_name} {$this->last_name}";
    }

}
