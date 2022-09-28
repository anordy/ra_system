<?php

namespace App\Http\Livewire\Investigation;

use App\Enum\TaxInvestigationStatus;
use App\Models\Investigation\TaxInvestigation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxInvestigationAssessmentTable extends DataTableComponent
{

    use LivewireAlert;

    public $model = TaxInvestigation::class;

    public function builder(): Builder
    {
        return TaxInvestigation::query()->with('business', 'location', 'taxType', 'createdBy')
            ->has('assessment')
            ->whereIn('tax_investigations.status', [TaxInvestigationStatus::APPROVED, TaxInvestigationStatus::LEGAL]);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects('created_by_type');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name'),
            Column::make('Period From', 'period_from'),
            Column::make('Period To', 'period_to'),
            Column::make('Filled By', 'created_by_id')
                ->format(fn ($value, $row) =>  $row->createdBy->full_name ?? ''),
            Column::make('Filled On', 'created_at')
                ->format(fn ($value) => Carbon::create($value)->toDateTimeString()),
            Column::make('Action', 'id')
                ->view('investigation.assessment.action')
                ->html(true),

        ];
    }
}
