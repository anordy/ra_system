<?php

namespace App\Http\Livewire\Finance;

use App\Models\Business;
use App\Models\BusinessStatus;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Yajra\DataTables\Services\DataTable;

class TaxpayerLedgerTable extends DataTableComponent
{
    use LivewireAlert;

    public function builder(): Builder
    {
        return Business::whereNotIn('businesses.status', [BusinessStatus::DRAFT, BusinessStatus::PENDING, BusinessStatus::REJECTED, BusinessStatus::CORRECTION])->orderBy('businesses.created_at', 'desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('ZTN', 'ztn_number')->sortable()->searchable(),
            Column::make('Business Name', 'name')->sortable()->searchable(),
            Column::make('TIN', 'tin')->sortable()->searchable(),
            Column::make('Buss. Reg. No.', 'reg_no')->sortable()->searchable(),
            Column::make('Mobile', 'mobile')->sortable()->searchable(),
            Column::make('Status', 'status')
                ->view('business.registrations.includes.status')->sortable()->searchable(),
            Column::make('Action', 'id')
                ->view('finance.includes.actions')
        ];
    }
}
