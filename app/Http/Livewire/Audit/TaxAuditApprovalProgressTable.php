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

        $query = WorkflowTask::with('pinstance', 'pinstance.location', 'pinstance.business', 'user')
            ->where('pinstance_type', TaxAudit::class)
            ->where('status', '!=', WorkflowTask::COMPLETED)
            ->whereHasMorph(
                'pinstance',
                [TaxAudit::class],
                function ($query) {
                    $query->where('forwarded_to_investigation', false)
                        ->whereHas('location.taxRegion', function ($query) {
                            if ($this->taxRegion == Region::LTD) {
                                $query->whereIn('location', [Region::LTD, Region::UNGUJA]);
                            } else {
                                $query->where('location', $this->taxRegion);
                            }
                        });
                });

        return $query;
    }



    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects('pinstance_type', 'user_type');

        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('user_type', 'user_id')->hideIf(true),
            Column::make('ZTN No')
                ->label(fn ($row) => $row->pinstance->business->ztn_number ?? ''),
            Column::make('TIN', 'pinstance.business.tin')
                ->label(fn ($row) => $row->pinstance->business->tin ?? ''),
            Column::make('Business Name', 'pinstance.business.name')
                ->label(fn ($row) => $row->pinstance->business->name ?? ''),
            Column::make('Business Location')
                ->label(fn ($row) => $row->pinstance->taxAuditLocationNames()),
            Column::make('Tax Types')
                ->label(fn ($row) => $row->pinstance->taxAuditTaxTypeNames()),
            Column::make('Period From', 'pinstance.period_from')
                ->label(fn ($row) => $row->pinstance->period_from ?? ''),
            Column::make('Period To', 'pinstance.period_to')
                ->label(fn ($row) => $row->pinstance->period_to ?? ''),
            Column::make('Filled By', 'pinstance.created_by_id')
                ->label(function ($row) {
                    $user = $row->pinstance->createdBy;
                    return $user->full_name ?? '';
                }),
            Column::make('From State', 'from_place')
                ->format(fn ($value) => strtoupper($value))
                ->sortable()->searchable(),
            Column::make('Current State', 'to_place')
                ->format(fn ($value) => strtoupper($value))
                ->sortable()->searchable(),
            Column::make('Action', 'pinstance_id')
                ->view('audit.approval.approval-progress')
        ];
    }
}
