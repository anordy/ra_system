<?php

namespace App\Http\Livewire\Vetting;

use App\Enum\VettingStatus;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Traits\WithSearch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Returns\TaxReturn;
use App\Traits\ReturnFilterTrait;
use Illuminate\Support\Facades\Gate;

class VettingApprovalTable extends DataTableComponent
{
    use  ReturnFilterTrait;

    protected $model     = TaxReturn::class;

    public $vettingStatus;

    public function mount($vettingStatus)
    {
        if (!Gate::allows('tax-returns-vetting-view')) {
            abort(403);
        }

        $this->vettingStatus = $vettingStatus;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['location_id', 'tax_type_id', 'financial_month_id']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
    }

    public function builder(): Builder
    {
        return TaxReturn::with('business', 'location', 'taxtype', 'financialMonth')
            ->whereNotIn('return_type', [PetroleumReturn::class])
            ->where('parent',0)
            ->where('vetting_status', $this->vettingStatus)
            ->orderBy('created_at', 'asc');
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Branch / Location', 'location.name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->location->name}";
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
