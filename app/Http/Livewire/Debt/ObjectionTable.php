<?php

namespace App\Http\Livewire\Debt;

use App\Models\Returns\ReturnStatus;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Verification\TaxVerification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class ObjectionTable extends DataTableComponent
{
    
    use LivewireAlert;


    public function builder(): Builder
    {
        $now = Carbon::now();
        // $temp = DB::table('tax_verifications')
        // ->join('tax_verification_assessments', 'tax_verification_assessments.verification_id', 'tax_verifications.id')
        // ->leftJoin('objections', 'objections.assesment_id', 'tax_verification_assessments.id')
        // ->join('businesses', 'businesses.id', 'tax_verifications.business_id')
        // ->selectRaw('*')
        // ->whereNull('objections.assesment_id')
        // ->where("tax_verification_assessments.status", '!=', ReturnStatus::COMPLETE)
        // ->whereRaw("DATEDIFF('". $now->format("Y-m-d") . "', tax_verification_assessments.created_at  ) >= 21")
        // ->get();

        // dd($temp);

        return TaxVerification::selectRaw('*')
        ->join('tax_verification_assessments', 'tax_verification_assessments.verification_id', 'tax_verifications.id')
        ->leftJoin('objections', 'objections.assesment_id', 'tax_verification_assessments.id')
        ->join('businesses', 'businesses.id', 'tax_verifications.business_id')
        ->whereNull('objections.assesment_id')
        ->where("tax_verification_assessments.status", '!=', ReturnStatus::COMPLETE)
        ->whereRaw("DATEDIFF('". $now->format("Y-m-d") . "', tax_verification_assessments.created_at  ) >= 21");
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
            Column::make('Business Name', 'businesses.name')
                ->sortable()
                ->searchable(),
            Column::make('Principal', 'assessment.principal_amount')
                ->sortable()
                ->searchable(),
            Column::make('Penalty', 'assessment.penalty_amount'),
            Column::make('Interest', 'assessment.interest_amount'),
            Column::make('Date of Notice Assessment', 'assessment.created_at'),
            Column::make('Action', 'id')->view('debts.includes.actions'),

            // Column::make('Buss. Reg. No.', 'reg_no'),
            // Column::make('Mobile', 'mobile'),

        ];
    }

}
