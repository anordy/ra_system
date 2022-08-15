<?php

namespace App\Http\Livewire\Investigation;

use App\Models\Returns\ReturnStatus;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Verification\TaxVerification;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class VerificationTable extends DataTableComponent
{
    
    use LivewireAlert;


    public function builder(): Builder
    {
        $now = Carbon::now();
        return TaxVerification::selectRaw('*')
        ->join('tax_assessments', 'tax_assessments.assessment_id', 'tax_verifications.id')
        ->leftJoin('objections', 'objections.assesment_id', 'tax_assessments.id')
        ->whereNull('objections.assesment_id')
        ->where("tax_assessments.assessment_type", TaxVerification::class)
        ->where("tax_assessments.status", '!=', ReturnStatus::COMPLETE)
        ->whereRaw("DATEDIFF('". $now->format("Y-m-d") . "', tax_assessments.created_at  ) >= 21")
        ->get();
        
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            // Column::make('Z Number', 'z_no')
            //     ->sortable()
            //     ->searchable(),
            // Column::make('Business Name', 'name')
            //     ->sortable()
            //     ->searchable(),
            // Column::make('TIN', 'tin'),
            // Column::make('Buss. Reg. No.', 'reg_no'),
            // Column::make('Mobile', 'mobile'),

        ];
    }

}
