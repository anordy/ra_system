<?php

namespace App\Http\Livewire\Returns\Cancellation;

use App\Enum\ReturnStatus;
use App\Models\Returns\TaxReturnCancellation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CancellationTable extends DataTableComponent
{
    public $status;

    public function mount($status) {
        $this->status = $status;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function builder(): Builder
    {
        return TaxReturnCancellation::where('tax_return_cancellations.status', $this->status);
    }


    public function columns(): array
    {
        return [
            Column::make(__('Business Name'), 'business.name')
                ->sortable()
                ->searchable(),
            Column::make(__('Location'), 'location.name')
                ->sortable()
                ->searchable(),
            Column::make(__('Tax Type'), 'taxtype.name')
                ->sortable()
                ->searchable(),
            Column::make(__('Currency'), $this->status === ReturnStatus::APPROVED ? 'trashedtaxreturn.currency' : 'taxreturn.currency')
                ->sortable()
                ->searchable(),
            Column::make(__('Total Tax'), $this->status === ReturnStatus::APPROVED ? 'trashedtaxreturn.outstanding_amount' : 'taxreturn.outstanding_amount')
                ->sortable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                })
                ->searchable(),
            Column::make(__('Submission Date'), 'created_at')
                ->sortable()
                ->format(function ($value, $row) {
                    return Carbon::create($value)->format('M d, Y');
                })
                ->searchable(),
            Column::make(__('Status'), 'status')->view('returns.cancellation.includes.status'),
            Column::make(__('Action'), 'id')->view('returns.cancellation.includes.actions'),
        ];
    }
}
