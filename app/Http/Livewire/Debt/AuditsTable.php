<?php

namespace App\Http\Livewire\Debt;

use Carbon\Carbon;
use App\Models\Debts\Debt;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class AuditsTable extends DataTableComponent
{
    
    use LivewireAlert;


    public function builder(): Builder
    {
        return Debt::query()->where('debts.category', 'audit')->orderBy('debts.created_at', 'desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['business_id','debt_id']);

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
            Column::make('Tax Type', 'taxtype.name'),
            Column::make('Total Debt', 'total')
                ->format(function ($value, $row) {
                    return number_format($value);
                }),
            Column::make('Action', 'id')->view('debts.audits.includes.actions'),

        ];
    }

}
