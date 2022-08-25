<?php

namespace App\Http\Livewire\Debt;

use App\Models\TaxType;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\Port\PortReturn;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class PortReturnTable extends DataTableComponent
{

    use LivewireAlert;
    public $taxType;
    public $tax;

    public function mount($taxType)
    {
        $this->taxType = $taxType;
        $this->tax = TaxType::where('code', $this->taxType)->first();
    }

    public function builder(): Builder
    {
        return PortReturn::query()->where('tax_type_id', $this->tax->id)->where('port_returns.status', '!=', ReturnStatus::COMPLETE)->orderBy('port_returns.created_at', 'desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['business_id', 'financial_month_id', 'tax_type_id']);
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
                ->format(function ($value, $row) {
                    return "{$row->business->taxpayer->first_name} {$row->business->taxpayer->last_name}";
                }),
            Column::make('Financial Month', 'financialMonth.name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->financialMonth->name} {$row->financialMonth->year->code}";
                }),
            Column::make('Tax Type', 'taxtype.name'),
            Column::make('Total Debt TZS', 'total_amount_due_with_penalties_tzs')
                ->format(function ($value, $row) {
                    return number_format($row->total_amount_due_with_penalties_tzs, 2);
                }),
            Column::make('Total Debt USD', 'total_amount_due_with_penalties_usd')
                ->format(function ($value, $row) {
                    return number_format($row->total_amount_due_with_penalties_usd, 2);
                }),
            // Column::make('Action', 'id')->view('debts.returns.includes.actions'),

        ];
    }
}
