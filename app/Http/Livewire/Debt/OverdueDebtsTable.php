<?php

namespace App\Http\Livewire\Debt;

use App\Models\Debts\Debt;
use App\Models\Returns\ReturnStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class OverdueDebtsTable extends DataTableComponent
{

    use LivewireAlert;

    public function builder(): Builder
    {
        return Debt::query()
                ->whereNotIn('debts.status',  [ReturnStatus::COMPLETE, ReturnStatus::PAID_BY_DEBT, ReturnStatus::ON_CLAIM])
                ->whereRaw("TIMESTAMPDIFF(DAY, debts.curr_due_date, CURDATE()) >= 60")
                ->orderBy('debts.created_at', 'desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['business_id','tax_type_id', 'business_location_id', 'recovery_measure_status']);
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
            Column::make('Tax Type', 'taxtype.name'),
            Column::make('Principal Amount', 'principal_amount')
            ->format(function ($value, $row) {
                return number_format($row->principal_amount, 2);
            }),
            Column::make('Penalty', 'penalty')
                ->format(function ($value, $row) {
                    return number_format($row->penalty, 2);
                }),
            Column::make('Interest', 'interest')
                ->format(function ($value, $row) {
                    return number_format($row->interest, 2);
                }),
            Column::make('Total Debt', 'total_amount')
                ->format(function ($value, $row) {
                    return number_format($row->total_amount, 2);
                }),
            Column::make('Due date', 'curr_due_date')
                ->format(function ($value, $row) {
                    return Carbon::create($value)->format('d M Y');
                }),
            Column::make('Payment Method', 'payment_method'),
            Column::make('Status', 'status')->view('debts.includes.status'),
            Column::make('Actions', 'id')->view('debts.overdue.actions'),
        ];
    }
}
