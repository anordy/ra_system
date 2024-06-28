<?php

namespace App\Http\Livewire\Payments;

use App\Models\ZmRecon;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\Log;
use App\Services\ZanMalipo\GepgResponse;
use Illuminate\Database\Eloquent\Builder;
use App\Services\Api\ZanMalipoInternalService;
use Exception;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class ReconEnquiriesTable extends DataTableComponent
{
    use CustomAlert, GepgResponse;

    protected $listeners = [
        'resend',
    ];

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
            Column::make('Reconciliation For', 'tnxdt')
                ->sortable()
                ->searchable(),
            Column::make('Reconciliation Type', 'reconcopt')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    if ($value == 1) {
                        return 'ZanMalipo Successful Transactions';
                    } else {
                        return 'ZanMalipo and Payment service provider Transaction Reconciliations';
                    }
                }),
            Column::make('Status', 'reconcstscode')
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

    public function triggerResendReconModal($id)
    {
        $this->customAlert('warning', 'Are you sure you want to resend reconciliation request ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Resend',
            'onConfirmed' => 'resend',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id,
            ],

        ]);
    }

    public function resend($id)
    {
        try {
            $enquireRecon = (new ZanMalipoInternalService)->requestRecon($id);
        } catch (Exception $e) {
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }
}
