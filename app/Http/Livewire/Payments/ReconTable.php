<?php

namespace App\Http\Livewire\Payments;

use App\Models\ZmReconTran;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ReconTable extends DataTableComponent
{
    use CustomAlert;
    public $recon_id;

    public function mount($recon_id)
    {
        $this->recon_id = decrypt($recon_id);
    }

    public function builder(): Builder
    {
        return ZmReconTran::where('recon_id', $this->recon_id)->orderBy('created_at', 'DESC');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setPerPageAccepted([15, 25, 50, 100]);
    }

    public function columns(): array
    {
        return [
            Column::make('Control No.', 'BillCtrNum')
                ->sortable()
                ->searchable(),
            Column::make('Currency', 'CCy'),
            Column::make('Bill Amount', 'PaidAmt')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                }),
            Column::make('Ctr Account No', 'CtrAccNum')
                ->sortable()
                ->searchable(),
            Column::make('Channel', 'UsdPayChnl')
                ->sortable()
                ->searchable(),
            Column::make('Payer Name', 'DptName'),
            Column::make('Payer Email', 'DptEmailAddr'),
            Column::make('Description', 'PspName'),
            Column::make('Actions', 'id')
                ->view('payments.includes.recon-transaction-actions')
        ];
    }
}
