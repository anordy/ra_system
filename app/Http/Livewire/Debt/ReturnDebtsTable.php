<?php

namespace App\Http\Livewire\Debt;

use App\Models\Business;
use App\Models\Debts\Debt;
use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\MmTransferReturn;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Returns\EmTransactionReturn;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\Returns\LumpSum\LumpSumReturn;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\TaxType;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class ReturnDebtsTable extends DataTableComponent
{

    use LivewireAlert;
    public $taxType;

    public function mount($taxType)
    {
        $this->taxType = $taxType;
    }

    public function builder(): Builder
    {

        $tax = TaxType::where('code', $this->taxType)->first();
        return Debt::query()->where('tax_type_id', $tax->id);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['business_id','tax_type_id', 'business_location_id']);
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Tax Payer', 'business.taxpayer.first_name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->business->taxpayer->first_name} {$row->business->taxpayer->last_name}";
                }),
            Column::make('Tax Type', 'taxtype.name'),
            Column::make('Principal Amount', 'principal_amount')
            ->format(function ($value, $row) {
                return number_format($row->principal_amount, 2);
            }),
            Column::make('Penalty', 'penalty')
                ->format(function ($value, $row) {
                    return number_format($row->penalty, 2);
                }),
            Column::make('Interest', 'interest')
                ->format(function ($value, $row) {
                    return number_format($row->interest, 2);
                }),
            Column::make('Total Debt', 'total_amount')
                ->format(function ($value, $row) {
                    return number_format($row->total_amount, 2);
                }),
            Column::make('Status', 'app_step')->view('debts.includes.status'),
        ];
    }
}
