<?php

namespace App\Http\Livewire\Debt;

use Carbon\Carbon;
use App\Models\Debts\Debt;
use Illuminate\Support\Facades\DB;
use App\Models\Returns\ReturnStatus;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Verification\TaxVerification;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class AssesmentsTable extends DataTableComponent
{
    
    use LivewireAlert;


    public function builder(): Builder
    {
        return Debt::query()->where('debts.category', 'assesment');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['business_id', 'financial_month_id', 'tax_type_id', 'debt_type_id']);

    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
            ->sortable()
            ->searchable(),
            Column::make('Tax Payer', 'business.taxpayer.first_name')
                ->sortable()
                ->searchable()
                ->format(function($value, $row) { 
                    return "{$row->business->taxpayer->first_name} {$row->business->taxpayer->last_name}"; 
                }),
            Column::make('Financial Month', 'financialMonth.name')
                ->sortable()
                ->searchable()
                ->format(function($value, $row) { 
                    return "{$row->financialMonth->name} {$row->financialMonth->year->code}"; 
                }),
            Column::make('Tax Type', 'taxtype.name'),
            Column::make('Total Debt', 'total')
                ->format(function ($value, $row) {
                    return number_format($value);
                }),
            Column::make('Date of Notice Assessment', 'assesment.created_at')
            ->format(function ($value, $row) {
                return Carbon::create($value)->format('M d, Y');
            }),
            Column::make('Action', 'id')->view('debts.verifications.includes.actions'),

        ];
    }

}
