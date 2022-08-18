<?php

namespace App\Http\Livewire\Investigation;

use App\Enum\TaxInvestigationStatus;
use App\Models\Investigation\TaxInvestigation;
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
        return TaxInvestigation::query()->with('business', 'location', 'taxType', 'taxReturn')
            ->has('assessment')
            ->where('tax_investigations.status', TaxInvestigationStatus::APPROVED);
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
            Column::make('Z_Number', 'location.zin'),
            Column::make('Business Name', 'business.name'),
            Column::make('Business Location', 'location.name'),
            Column::make('Tax Type', 'taxType.name'),
            Column::make('Action', 'id')
                ->view('investigation.assessment.action')
                ->html(true),

        ];
    }
}
