<?php

namespace App\Http\Livewire\Vetting;

use App\Enum\VettingStatus;
use App\Models\Region;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\TaxReturn;
use App\Models\TaxType;
use App\Models\WorkflowTask;
use App\Traits\ReturnFilterTrait;
use App\Traits\VettingFilterTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class VettingApprovalTableNtl extends DataTableComponent
{
    use VettingFilterTrait;

    protected $model = TaxReturn::class;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];

    public $data = [];

    public $vettingStatus, $orderBy;

    public function mount($vettingStatus)
    {
        if (!Gate::allows('tax-returns-vetting-view')) {
            abort(403);
        }

        $this->vettingStatus = $vettingStatus;

        if ($this->vettingStatus == VettingStatus::VETTED) {
            $this->orderBy = 'DESC';
        } else {
            $this->orderBy = 'ASC';
        }
    }

    public function filterData($data)
    {
        $this->data = $data;
        $this->emit('$refresh');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['location_id', 'tax_type_id', 'financial_month_id']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }


    public function builder(): Builder
    {
        $query = TaxReturn::with('business', 'location', 'taxtype', 'financialMonth', 'location.taxRegion')
            ->whereNotIn('return_type', [PetroleumReturn::class, LumpSumReturn::class])
            ->where('parent', 0)
            ->whereHas('location.taxRegion', function ($query) {
                $query->where('location', Region::NTRD);
            })
            ->where('vetting_status', $this->vettingStatus)
            ->whereHas('pinstance', function ($query) {
                $query->whereHas('actors', function ($query) {
                    $query->where('user_id', auth()->id());
                });
            });

        // Apply filters
        $returnTable = TaxReturn::getTableName();
        $query = $this->dataFilter($query, $this->data, $returnTable);
        $query->orderBy('created_at', $this->orderBy);

        return $query;
    }

    public function columns(): array
    {
        return [
            Column::make('Taxpayer Name', 'business.taxpayer_name')
                ->format(function ($value, $row) {
                    return $value ?? 'N/A';
                })
                ->sortable()->searchable(),
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Old ZRA No', 'business.previous_zno')
                ->sortable()
                ->searchable(),
            Column::make('Branch', 'location.name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->location->name}";
                }),
            Column::make('Tax Region', 'location.tax_region_id')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->location->taxRegion->name}";
                }),
            Column::make('Tax Type', 'taxtype.name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->taxtype->name}";
                }),
            Column::make('Financial Month', 'financialMonth.name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->financialMonth->name} {$row->financialMonth->year->code}";
                }),
            Column::make('Total', 'total_amount')
                ->sortable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                })
                ->searchable(),
            Column::make('Currency', 'currency')
                ->sortable()
                ->searchable(),
            Column::make('Status', 'vetting_status')
                ->view('vetting.includes.status')
                ->searchable()
                ->sortable(),
            Column::make('Payment Status', 'payment_status')
                ->view('returns.includes.payment-status')
                ->searchable()
                ->sortable()
                ->hideIf($this->vettingStatus != VettingStatus::VETTED),
            Column::make('Filed On', 'created_at')
                ->sortable()
                ->format(function ($value, $row) {
                    return Carbon::create($value)->format('M d, Y H:i');
                })
                ->searchable(),
            Column::make('Action', 'id')->view('vetting.includes.actions'),
        ];
    }
}
