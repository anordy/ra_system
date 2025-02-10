<?php

namespace App\Models\Procurement;

use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory, WorkflowTrait;

    protected $table = "pr_contracts";
    protected $guarded = [];

    public function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime'
        ];
    }


}
