<?php

namespace App\Http\Livewire\Reports\Returns\Previews;

use App\Models\Returns\LumpSum\LumpSumReturn;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Traits\ReturnReportTrait;

class LumpSumPreviewTable extends DataTableComponent
{
    use LivewireAlert, ReturnReportTrait;

    public $parameters;

    public function mount($parameters)
    {
        // dd($parameters);
        $this->parameters = $parameters;
    }
    
    public function builder(): Builder
    {
        $stampDuties =$this->getRecords(LumpSumReturn::query(), $this->parameters);

        return $stampDuties;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['lump_sum_returns.business_id', 'lump_sum_returns.business_location_id', 'lump_sum_returns.financial_month_id', 'lump_sum_returns.financial_year_id', 'lump_sum_returns.created_at', 'lump_sum_returns.filed_by_id', 'lump_sum_returns.filed_by_type']);
    }

    public function columns(): array
    {
        return [
            Column::make('Register Date', 'created_at')
                ->format(function ($value, $row) {
                    return date('d/m/Y', strtotime($value));
                })
                ->searchable()
                ->sortable(),
            Column::make('Business', 'business_id')
                ->format(
                    function ($value, $row) {
                        return $row->business->name;
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make('Business Location', 'business_location_id')
                ->format(
                    function ($value, $row) {
                        return $row->businessLocation->name;
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make('Financial Quaters', 'quarter_name')
                ->searchable()
                ->sortable(),
            //financial year
            Column::make('Financial Year', 'financial_year_id')
                ->format(
                    function ($value, $row) {
                        return $row->financialYear->name;
                    }
                )
                ->searchable()
                ->sortable(),
            //filed by
            Column::make('Filed By', 'id')
                ->format(
                    function ($value, $row) {
                        return $row->taxpayer->fullName;
                    }
                )
                ->searchable()
                ->sortable(),
            //currency
            Column::make('Currency', 'currency')
                ->searchable()
                ->sortable(),
            //total_amount_due
            Column::make('Total Amount Due', 'total_amount_due')
                ->format(
                    function ($value, $row) {
                        return number_format($value, 2);
                    }
                )
                ->searchable()
                ->sortable(),
            //total_amount_due_with_penalties
            Column::make('Total Amount Due With Penalties', 'total_amount_due_with_penalties')
                ->format(
                    function ($value, $row) {
                        return number_format($value, 2);
                    }
                )
                ->searchable()
                ->sortable(),
            //filing_due_date
            Column::make('Filing Due Date', 'filing_due_date')
                ->format(
                    function ($value, $row) {
                        if (!$value) {
                            return '-';
                        }

                        return date('d/m/Y', strtotime($value));
                    }
                )
                ->searchable()
                ->sortable(),
            //file status
            Column::make('File Status', 'id')
                ->format(
                    function ($value, $row) {
                        if ($row->created_at < $row->filing_due_date) {
                            return '<span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
                            <i class="bi bi-check-circle"></i>
                                        In Time
                                    </span>';
                        } else {
                            return '<span class="badge badge-danger py-1 px-2" style="border-radius: 1rem; background: rgba(220,53,53,0.35); color: #cf1c1c; font-size: 85%">
                            <i class="bi bi-clock"></i>
                                        Late
                                    </span>';
                        }
                    }
                )
                ->searchable()
                ->sortable()
                ->html(),
            //payment_due_date
            Column::make('Payment Due Date', 'payment_due_date')
                ->format(
                    function ($value, $row) {
                        return date('d/m/Y', strtotime($value));
                    }
                )
                ->searchable()
                ->sortable(),
            //payment status
            Column::make("Payment Status", "paid_at")
                ->format(
                    function ($value, $row) {
                        if ($row->created_at == null || $row->paid_at == null) {
                            return '-';
                        } else {
                            if ($row->paid_at < $row->payment_due_date) {
                                return '<span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
                                <i class="bi bi-check-circle"></i>
                                    In-Time
                                        </span>';
                            } else {
                                return '<span class="badge badge-danger py-1 px-2" style="border-radius: 1rem; background: rgba(220,53,53,0.35); color: #cf1c1c; font-size: 85%">
                                <i class="bi bi-clock"></i>
                                            Late
                                        </span>';
                            }
                        }
                    }
                )
                ->searchable()
                ->sortable()
                ->html(),
        ];
    }
}
