<?php

namespace App\Http\Livewire\Payments;

use App\Enum\PaymentStatus;
use App\Models\ZmBill;
use App\Traits\ReconReportTrait;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ReconReportTable extends DataTableComponent
{
    use LivewireAlert;
    use ReconReportTrait;

    public $parameters=[];

    public function mount($parameters){
        $this->parameters = $parameters;
    }

    public function builder(): Builder
    {
        return $this->getBillBuilder($this->parameters);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects('tax_type_id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
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
                    return $value ? $value : 'Pending';
                }),
            Column::make('Currency', 'currency')
                ->sortable()
                ->searchable(),
            Column::make('Bill Amount', 'amount')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return $value ? $value : 'Pending';
                }),
            Column::make('Tax Type', 'tax_type_id')
                ->label(fn ($row) => $row->taxType->name ?? 'N/A')
                ->sortable()
                ->searchable(),
            Column::make('ZanMalipo Recon Status', 'zm_recon_status')
                ->sortable()
                ->searchable(),
            Column::make('Bank Recon Status', 'bank_recon_status')
                ->sortable()
                ->searchable(),
            Column::make('Actions', 'id')
                ->view('payments.includes.actions')
        ];
    }
}
