<?php

namespace App\Models;

use App\Traits\WorkflowTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WithholdingAgent extends Model implements Auditable
{
    use HasFactory, SoftDeletes, WorkflowTrait, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function street()
    {
        return $this->belongsTo(Street::class, 'street_id');
    }

    public function latestResponsiblePerson()
    {
        return $this->hasOne(WaResponsiblePerson::class)->latest();
    }

    public function responsiblePersons()
    {
        return $this->hasMany(WaResponsiblePerson::class);
    }


    public function zwnGeneration()
    {

        try {
            $ztn_number = 'ZW';

            // Append region prefix
            switch ($this->region->location) {
                case Region::UNGUJA:
                    $ztn_number = $ztn_number . '05';
                    $mainRegion = MainRegion::where('prefix', MainRegion::UNG)->firstOrFail();
                    break;
                case Region::PEMBA:
                    $ztn_number = $ztn_number . '06';
                    $mainRegion = MainRegion::where('prefix', MainRegion::PMB)->firstOrFail();
                    break;
                default:
                    Log::error("Invalid Main Region selected!");
                    abort(404);
            }

            // Append year
            $ztn_number = $ztn_number . Carbon::now()->format('y');

            $value = $mainRegion->withholding_agent_registration_count + 1;

            //Append Number 000001 - 999999
            $ztn_number = $ztn_number . str_pad($value, 5, "0", STR_PAD_LEFT);

            $mainRegion->withholding_agent_registration_count = $value;
            $mainRegion->save();

            $this->wa_number = $ztn_number;
            $this->save();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class, 'taxpayer_id');
    }
}
