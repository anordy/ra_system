<?php

namespace App\Http\Livewire\Audit;

use App\Models\Region;
use App\Models\TaxAudit\TaxAudit;
use App\Models\WorkflowTask;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxAuditApprovalProgressTable extends DataTableComponent
{
    use CustomAlert;
    public $taxRegion;
    public $orderBy;

    public $model = WorkflowTask::class;

    public function mount($taxRegion = null)
    {
        $this->taxRegion = $taxRegion;
    }

    public function builder(): Builder
    {

        $query = TaxAudit::query()
            ->with('business', 'location', 'taxType', 'createdBy', 'pinstance')
            ->where('tax_audits.forwarded_to_investigation', false)
            ->whereHas('pinstance', function ($query) {
                $query->where('status', '!=', 'completed');
            })
            ->whereHas('location.taxRegion', function ($query) {
                if ($this->taxRegion == Region::LTD) {
                    $query->whereIn('location', [Region::LTD, Region::UNGUJA]);
                } else {
                    $query->where('location', $this->taxRegion);
                }
            });

        return $query;
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
            Column::make('ZTN No', 'business.ztn_number'),
            Column::make('TIN', 'business.tin'),
            Column::make('Business Name', 'business.name'),
            Column::make('Location', 'location_id')->format(function ($value, $row){
                return $row->taxAuditLocationNames();
            }),
            Column::make('Tax Types', 'tax_type_id')->format(function ($value, $row){
                return $row->taxAuditTaxTypeNames();
            }),
            Column::make('Period From', 'period_from')->format(function ($value, $row){
                return $row->period_from ? $row->period_from->toFormattedDateString() : 'N/A';
            }),
            Column::make('Period To', 'period_to')->format(function ($value, $row){
                return $row->period_to ? $row->period_to->toFormattedDateString() : 'N/A';
            }),
            Column::make('Filled On', 'created_at')
                ->format(fn ($value) => Carbon::create($value)->format('d-m-Y')),
            Column::make('From State', 'pinstance.from_place')
                ->format(fn ($value) => strtoupper($value))
                ->sortable()->searchable(),
            Column::make('Current State', 'pinstance.to_place')
                ->format(fn ($value) => strtoupper($value))
                ->sortable()->searchable(),
            Column::make('Action', 'pinstance.id')
                ->view('audit.approval.approval-progress')
        ];
    }
}
