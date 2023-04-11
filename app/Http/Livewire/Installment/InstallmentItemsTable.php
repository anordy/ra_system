<?php

namespace App\Http\Livewire\Installment;

use App\Models\Installment\InstallmentItem;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class InstallmentItemsTable extends DataTableComponent
{
    use CustomAlert;

    public $installment;

    public function builder(): Builder
    {
        return InstallmentItem::where('installment_id', $this->installment->id)
            ->whereHas('bill.bill_payments')
            ->with('bill.bill_payments')
            ->orderBy('created_at', 'DESC');
    }

    public function columns(): array
    {
        return [
            Column::make('Payment Ref', 'id')
                ->format(function($value, $row){
                    return $row->bill->bill_payments->first()->pay_ref_id ?? 'N/A';
                }),
            Column::make('PSP Receipt No', 'id')
                ->format(function($value, $row){
                    return $row->bill->bill_payments->first()->psp_receipt_number ?? 'N/A';
                }),
            Column::make('Paid Amount', 'amount')
                ->format(function ($value, $row){
                    return number_format($value,2) . ' ' . $row->currency;
                }),
            Column::make('Currency', 'currency')
                ->sortable()
                ->searchable(),
            Column::make('Paid at', 'paid_at')
                ->sortable()
                ->searchable()
                ->format(function ($value){
                    return $value ?? '-';
                }),
            Column::make('Actions', 'status')
                ->view('installment.includes.installment-items-actions'),
        ];
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }
}