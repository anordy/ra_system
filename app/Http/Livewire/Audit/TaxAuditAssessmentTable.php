<?php

namespace App\Http\Livewire\Audit;

use App\Enum\TaxAuditStatus;
use App\Models\TaxAudit\TaxAudit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxAuditAssessmentTable extends DataTableComponent
{

    use CustomAlert;
    public $taxRegion;
    public $orderBy;

    public $model = TaxAudit::class;

    public function mount($taxRegion)
    {
        // if (!Gate::allows('tax-returns-vetting-view')) {
        //     abort(403);
        // }

        $this->taxRegion = $taxRegion;
    }

    public function builder(): Builder
    {
        return TaxAudit::query()->with('business', 'location', 'taxType', 'taxAuditLocations', 'createdBy')
            ->has('assessment')
            ->where('tax_audits.status', TaxAuditStatus::APPROVED)
            ->whereHas('location.taxRegion', function ($query) {
                $query->where('location', $this->taxRegion); //this is filter by department
            });
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['created_by_type', 'created_by_id']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setThAttributes(function (Column $column) {
            if ($column->getTitle() == 'Tax Types') {
                return [
                    'style' => 'width: 20%;',
                ];
            }
            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make('ZTN No', 'business.ztn_number'),
            Column::make('TIN', 'business.tin'),
            Column::make('Business Name', 'business.name'),
            Column::make('Business Location')
                ->label(fn ($row) => $row->taxAuditLocationNames()),
            Column::make('Tax Types')
                ->label(fn ($row) => $row->taxAuditTaxTypeNames()),
            Column::make('Period From', 'period_from')
                ->format(fn ($value) => Carbon::create($value)->format('d-m-Y')),
            Column::make('Period To', 'period_to')
                ->format(fn ($value) => Carbon::create($value)->format('d-m-Y')),
            Column::make('Created By')
                ->label(fn ($row) => $row->createdBy->full_name ?? ''),
            Column::make('Created On', 'created_at')
                ->label(fn ($row) => Carbon::create($row->created_at ?? null)->format('d-m-Y')),
            Column::make('Action', 'id')
                ->view('audit.assessment.action')
                ->html(true),

        ];
    }
}
