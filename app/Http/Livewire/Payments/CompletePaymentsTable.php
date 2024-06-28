<?php

namespace App\Http\Livewire\Payments;

use App\Enum\GeneralConstant;
use App\Enum\PaymentStatus;
use App\Models\ZmBill;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CompletePaymentsTable extends DataTableComponent
{
    use CustomAlert;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];

    public $data = [];

    public function filterData($data)
    {
        $this->data = $data;
        $this->emit('$refresh');
    }

    public function builder(): Builder
    {
        $data   = $this->data;
        $filter = (new ZmBill())->newQuery();

        if (isset($data['tax_type_id']) && $data['tax_type_id'] != GeneralConstant::ALL) {
            $filter->Where('tax_type_id', $data['tax_type_id']);
        }
        if (isset($data['currency']) && $data['currency'] != GeneralConstant::ALL) {
            $filter->Where('currency', $data['currency']);
        }
        if (isset($data['range_start']) && isset($data['range_end'])) {
            $filter->WhereBetween('created_at', [$data['range_start'],$data['range_end']]);
        }

        if (isset($data['pbz_status']) && $data['pbz_status'] == GeneralConstant::NOT_APPLICABLE){
            $filter->whereNull('pbz_status');
        }

        if (isset($data['pbz_status']) && $data['pbz_status'] == GeneralConstant::PAID){
            $filter->where('pbz_status', 'paid');
        }

        if (isset($data['pbz_status']) && $data['pbz_status'] == GeneralConstant::REVERSED){
            $filter->where('pbz_status', 'reversed');
        }

        return $filter->with(['billable', 'payment'])->whereIn('status', [PaymentStatus::PAID])->orderBy('created_at', 'DESC');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['tax_type_id', 'billable_type', 'billable_id']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
        $this->setPerPageAccepted([15, 25, 50, 100]);
    }

    public function columns(): array
    {
        return [
            Column::make('Control No.', 'control_number')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return $value ? $value : 'N/A';
                }),
            Column::make('Bill Amount', 'amount')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                }),
            Column::make('Paid Amount', 'paid_amount')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return number_format($value, 2) . " $row->currency";
                }),
            Column::make('Tax Type', 'tax_type_id')
                ->label(fn ($row) => $row->taxType->name ?? 'N/A')
                ->sortable()
                ->searchable(),
            Column::make('Business Name', 'billable')
                ->label(fn ($row) => $row->billable->business->name ?? 'N/A')
                ->sortable()
                ->searchable(),
            Column::make('PSP Name', 'payment')
                ->label(fn ($row) => $row->payment->psp_name ?? 'N/A')
                ->sortable()
                ->searchable(),
            Column::make('Payer Name', 'payer_name')
                ->sortable()
                ->searchable(),
            Column::make('Description', 'description')
                ->sortable()
                ->searchable(),
            Column::make('Status', 'status'),
            Column::make('PBZ Status', 'pbz_status')->view('payments.includes.pbz-status'),
            Column::make('Actions', 'id')
                ->view('payments.includes.actions'),
        ];
    }
}
