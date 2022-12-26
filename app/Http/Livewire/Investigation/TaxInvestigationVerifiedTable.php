<?php

namespace App\Http\Livewire\Investigation;

use App\Enum\TaxInvestigationStatus;
use App\Models\Investigation\TaxInvestigation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxInvestigationVerifiedTable extends DataTableComponent
{

    use LivewireAlert;

    public $model = TaxInvestigation::class;

    public function builder(): Builder
    {
        return TaxInvestigation::query()->with('business', 'location', 'taxType')
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
                ->label(fn ($row) => $row->taxInvestigationLocationNames()),
            Column::make('Tax Types')
                ->label(fn ($row) => $row->taxInvestigationTaxTypeNames()),
            Column::make('Period From', 'period_from')
                ->format(fn ($value) => Carbon::create($value)->format('d-m-Y')),
            Column::make('Period To', 'period_to')
                ->format(fn ($value) => Carbon::create($value)->format('d-m-Y')),
            Column::make('Created By', 'created_by_id')
                ->label(fn ($row) => $row->createdBy->full_name ?? ''),
            Column::make('Created On', 'created_at')
                ->label(fn ($row) => Carbon::create($row->created_at ?? null)->format('d-m-Y')),
            Column::make('Action', 'id')
                ->view('investigation.verified.action')
                ->html(true),

        ];
    }
}
