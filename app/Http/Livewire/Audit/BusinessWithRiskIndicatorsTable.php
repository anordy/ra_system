<?php

namespace App\Http\Livewire\Audit;

use App\Models\BusinessLocation;
use App\Models\TaxAudit\TaxAudit;
use App\Models\TaxAudit\TaxAuditLocation;
use App\Models\TaxAudit\TaxAuditTaxType;
use App\Models\WorkflowTask;
use App\Traits\WithSearch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class BusinessWithRiskIndicatorsTable extends DataTableComponent
{
    use CustomAlert;

    public $model = WorkflowTask::class;

    public function builder(): Builder
    {

        return BusinessLocation::whereHas('taxVerifications', function ($query) {
            $query->whereHas('riskIndicators');
        })
        ->with('taxVerifications.riskIndicators'); // Eager load risk indicators;

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
            Column::make('ZRA No', 'zin'),
            Column::make('TIN', 'business.tin'),
            Column::make('Business Name', 'business.name')->searchable(),
            Column::make('Business Location', 'name')->searchable(),
            Column::make('Region', 'region.name')->sortable()->searchable(),
            Column::make('District', 'district.name')->sortable()->searchable(),
            Column::make('Ward', 'ward.name')->sortable()->searchable(),
            Column::make('Filled On', 'created_at')
            ->format(fn ($value) => Carbon::create($value)->toDateString())->sortable()->searchable(),
            Column::make('Action', 'id')
                ->view('audit.business.action')
                ->html(true),
        ];
    }
}
