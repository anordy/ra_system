<?php

namespace App\Http\Livewire\Debt;

use App\Enum\BillStatus;
use App\Enum\ReturnCategory;
use App\Models\Region;
use App\Models\Returns\TaxReturn;
use App\Traits\VettingFilterTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class ReturnOverdueDebtsTable extends DataTableComponent
{
    use CustomAlert, VettingFilterTrait;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];
    public $data = [];

    public function filterData($data)
    {
        $this->data = $data;
        $this->emit('$refresh');
    }

    public $department;
    public $locations = [];

    public function mount($department) {
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

    public function builder(): Builder
    {
        $query = TaxReturn::query()
            ->whereIn('return_category', [ReturnCategory::OVERDUE, ReturnCategory::DEBT])
            ->where('payment_status', '!=', BillStatus::COMPLETE)
            ->whereHas('location.taxRegion', function ($query) {
                $query->whereIn('location', $this->locations);
            });

        $returnTable = TaxReturn::getTableName();
        return $this->dataFilter($query, $this->data, $returnTable);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['tax_returns.business_id', 'tax_type_id', 'location_id', 'financial_month_id']);
    }

    public function columns(): array
    {
        return [
            Column::make('ZIN', 'location.zin')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->location->zin}";
                }),
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Branch', 'location.name')
                ->sortable()
                ->searchable(),
            Column::make('Tax Type', 'taxtype.name'),
            Column::make('Financial Month', 'financialMonth.name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->financialMonth->name} {$row->financialMonth->year->code}";
                }),
            Column::make('Total Payable Amount', 'total_amount')
                ->format(function ($value, $row) {
                    return number_format($row->total_amount, 2);
                }),
            Column::make('Outstanding Amount', 'outstanding_amount')
                ->format(function ($value, $row) {
                    return number_format($row->outstanding_amount, 2);
                }),
            Column::make('Days', 'filing_due_date')
                ->format(function ($value, $row) {
                    return Carbon::now()->diffInDays(Carbon::create($row->filing_due_date)->addMonth()->endOfDay());
                }),
            Column::make('Payment Status', 'payment_status')->view('debts.includes.status'),
            Column::make('Action', 'id')->view('debts.includes.actions'),
        ];
    }
}
