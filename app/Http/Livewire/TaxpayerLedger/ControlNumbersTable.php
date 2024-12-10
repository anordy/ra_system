<?php

namespace App\Http\Livewire\TaxpayerLedger;

use App\Models\Region;
use App\Models\TaxpayerLedger\TaxpayerLedgerPayment;
use App\Models\WithholdingAgent;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ControlNumbersTable extends DataTableComponent
{
    use CustomAlert;

    public $status, $department, $locations = [];

    public function mount($status, $department) {
        $this->status = $status;
        $this->department = $department;


        if ($department === Region::DTD) {
            $this->locations = [Region::DTD];
        } else if ($department === Region::LTD) {
            $this->locations = [Region::LTD, Region::UNGUJA];
        } else if ($department === Region::PEMBA) {
            $this->locations = [Region::PEMBA];
        } else if ($department === Region::NTRD) {
            $this->locations = [Region::NTRD];
        } else {
            $this->locations = [Region::DTD, Region::LTD, Region::PEMBA, Region::NTRD];
        }
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
        return TaxpayerLedgerPayment::query()
            ->where('taxpayer_ledger_payments.status', $this->status)
            ->whereHas('location.taxRegion', function ($query) {
                $query->whereIn('location', $this->locations);
            })
            ->orderBy('taxpayer_ledger_payments.created_at', 'ASC');
    }

    public function columns(): array
    {
        return [
            Column::make('Control Number', 'status')
                ->sortable()
                ->format(function($value, $row) {
                    return $row->latestBill->control_number ?? 'N/A';
                })
                ->searchable(),
            Column::make('Debit Numbers', 'ledger_ids')
                ->format(function($value, $row) {
                    return $row->debit_numbers ?? 'N/A';
                }),
            Column::make('Location', 'location_id')
                ->format(function($value, $row) {
                    return $row->location->name ?? 'N/A';
                }),
            Column::make('ZTN Number', 'marking')
                ->format(function($value, $row) {
                    return $row->location->zin ?? 'N/A';
                }),
            Column::make('Currency', 'currency')
                ->sortable()
                ->searchable(),
            Column::make('Total Amount', 'total_amount')
                ->sortable()
                ->searchable()
                ->format(function($value, $row) {
                    return number_format($value ?? 0, 2);
                }),
            Column::make('Approval Status', 'staff_id')
                ->view('taxpayer-ledger.includes.status'),
            Column::make('Bill Status', 'updated_at')
                ->view('taxpayer-ledger.includes.bill-status'),
            Column::make('Action', 'id')
                ->view('taxpayer-ledger.includes.actions')
        ];
    }


}
