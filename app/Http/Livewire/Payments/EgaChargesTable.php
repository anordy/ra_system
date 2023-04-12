<?php

namespace App\Http\Livewire\Payments;

use App\Traits\PaymentReportTrait;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class EgaChargesTable extends DataTableComponent
{
    use CustomAlert;
    use PaymentReportTrait;

    public $range_start;
    public $range_end;
    public $currency;
    public $payment_status;
    public $charges_type;

    public function mount($parameters){
        $this->range_start = $parameters['range_start'];
        $this->range_end = $parameters['range_end'];
        $this->currency = $parameters['currency'];
        $this->payment_status = $parameters['payment_status'];
        $this->charges_type = $parameters['charges_type'];
    }

    public function builder(): Builder
    {
        return $this->getEgaChargesQuery($this->range_start,$this->range_end,$this->currency,$this->payment_status,$this->charges_type);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects('zm_bill_id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setPerPageAccepted([15, 25, 50, 100]);
    }

    public function columns(): array
    {
        return [
            Column::make('Control Number', 'bill.control_number')
                ->sortable()
                ->searchable(),
            Column::make('Currency', 'currency')
                ->sortable()
                ->searchable(),
            Column::make('Amount', 'amount')
                ->sortable()
                ->searchable()
                ->format(function ($value) {
                    return number_format($value, 2);
                }),
            Column::make('Payment Status', 'id')
                ->sortable()
                ->searchable()
                ->format(function ($value,$row) {
                    $status = $row->bill->status;
                    if ($status == 'paid') {
                        return <<< HTML
                            <span class="badge badge-success">$status</span>
                        HTML;
                    }else{
                        return <<< HTML
                            <span class="badge badge-danger">$status</span>
                        HTML;
                    }
                })
                ->html(true),
            Column::make('Description', 'bill.description')
                ->sortable()
                ->searchable(),
            Column::make('Payer Name', 'bill.payer_name')
                ->sortable()
                ->searchable(),
            Column::make('Payer Email', 'bill.payer_email')
                ->sortable()
                ->searchable(),
            Column::make('Payer Phone', 'bill.payer_phone_number')
                ->sortable()
                ->searchable(),
            
        ];
    }
}
