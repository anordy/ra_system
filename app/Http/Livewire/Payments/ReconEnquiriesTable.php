<?php

namespace App\Http\Livewire\Payments;

use App\Models\ZmRecon;
use App\Services\ZanMalipo\GepgResponse;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ReconEnquiriesTable extends DataTableComponent
{
    use LivewireAlert, GepgResponse;

    public function builder(): Builder
    {
        return ZmRecon::orderBy('created_at', 'DESC');
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
            Column::make('Reconciliation For', 'TnxDt')
                ->sortable()
                ->searchable(),
            Column::make('Reconciliation Type', 'ReconcOpt')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    if ($value == 1) {
                        return 'ZanMalipo Successful Transactions';
                    } else {
                        return 'ZanMalipo and Payment service provider Transaction Reconciliations';
                    }
                }),
            Column::make('Status', 'ReconcStsCode')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    if ($value) {
                        return $this->getResponseCodeStatus($value)['message'];
                    } else {
                        return 'N/A';
                    }
                }),
            Column::make('Date of Request', 'created_at'),
            Column::make('Actions', 'id')
                ->view('payments.includes.recon-actions')
        ];
    }
}
