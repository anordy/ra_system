<?php

namespace App\Http\Livewire\Reports\Returns\Previews;

use App\Models\Returns\Port\PortReturn;
use App\Models\TaxType;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

use App\Traits\ReturnReportTrait;

class AirPortPreviewTable extends DataTableComponent
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
        $taxType = TaxType::where('code', 'sea-service-transport-charge')->first();
        $seaPorts =$this->getRecords(PortReturn::query()->where('tax_type_id', $taxType->id), $this->parameters); 
        return $seaPorts;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['port_returns.business_id', 'port_returns.business_location_id', 'port_returns.financial_month_id', 'port_returns.financial_year_id', 'port_returns.created_at', 'port_returns.filed_by_id', 'port_returns.filed_by_type']);
    }

    public function columns(): array
    {
        return [
            Column::make("Register Date", "created_at")
                ->format(function ($value, $row) {
                    return date('d/m/Y', strtotime($value));
                })
                ->searchable()
                ->sortable(),
            Column::make("Business", "business_id")
                ->format(
                    function ($value, $row) {
                        return $row->business->name;
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make("Business Location", "business_location_id")
                ->format(
                    function ($value, $row) {
                        return $row->branch->name;
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make("Financial Month", "financial_month_id")
                ->format(
                    function ($value, $row) {
                        return $row->financialMonth->name;
                    }
                )
                ->searchable()
                ->sortable(),
            //financial year
            Column::make("Financial Year", "financial_year_id")
                ->format(
                    function ($value, $row) {
                        return $row->financialYear->name;
                    }
                )
                ->searchable()
                ->sortable(),
            //filed by
            Column::make("Filed By", "id")
                ->format(
                    function ($value, $row) {
                        return $row->taxpayer->fullName;
                    }
                )
                ->searchable()
                ->sortable(),
            //currency
            Column::make("Currency", "currency")
                ->searchable()
                ->sortable(),
            //total_vat_payable_tzs
            Column::make("Vat Amount (TZS)", "total_vat_payable_tzs")
                ->format(
                    function ($value, $row) {
                        if($value == null){
                            return '-';
                        }
                        return number_format($value, 2);
                    }
                )
                ->searchable()
                ->sortable(),
            //total_vat_payable_usd
            Column::make("Vat Amount (USD)", "total_vat_payable_usd")
                ->format(
                    function ($value, $row) {
                        if($value == null){
                            return '-';
                        }
                        return number_format($value, 2);
                    }
                )
                ->searchable()
                ->sortable(),
            //total_amount_due_with_penalties_tzs
            Column::make("Amount Due With Penalties(TZS)", "total_amount_due_with_penalties_tzs")
                ->format(
                    function ($value, $row) {
                        if($value == null){
                            return '-';
                        }
                        return number_format($value, 2);
                    }
                )
                ->searchable()
                ->sortable(),
             //total_amount_due_with_penalties_usd
            Column::make("Amount Due With Penalties(USD)", "total_amount_due_with_penalties_usd")
                ->format(
                    function ($value, $row) {
                        if($value == null){
                            return '-';
                        }
                        return number_format($value, 2);
                    }
                )
                ->searchable()
                ->sortable(),
            //filing_due_date
            Column::make("Filing Due Date", "filing_due_date")
                ->format(
                    function ($value, $row) {
                        if(!$value){
                            return '-';
                        }
                        return date('d/m/Y', strtotime($value));
                    }
                )
                ->searchable()
                ->sortable(),
            //file status
            Column::make("File Status", "id")
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
            Column::make("Payment Due Date", "payment_due_date")
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
                        if ($row->created_at < $row->payment_due_date) {
                            return '<span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
                            <i class="bi bi-check-circle"></i>
                                        Not Late
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
        ];
    }
}
