<?php

namespace App\Http\Livewire\Audit;

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

class TaxAuditApprovalTable extends DataTableComponent
{
    use CustomAlert;

    public $taxRegion;
    public $orderBy;

    public $model = WorkflowTask::class;

    /**
     * Mount the component.
     *
     * @param  mixed  $taxRegion The tax region for the component.
     * @return void
     */
    public function mount($taxRegion = null)
    {
        // if (!Gate::allows('tax-returns-vetting-view')) {
        //     abort(403);
        // }

        $this->taxRegion = $taxRegion;
    }

    /**
     * Get the query builder for the table.
     *
     * @return \Illuminate\Database\Eloquent\Builder The query builder for the table.
     */
    public function builder(): Builder
    {
        $data = WorkflowTask::with('pinstance', 'pinstance.location', 'pinstance.business', 'user')
            ->where('pinstance_type', TaxAudit::class)
            ->where('status', '!=', 'completed')
            ->where('owner', 'staff');
        //TODO: return to the original function
        // ->whereHas('actors', function ($query) {
        //     $query->where('user_id', auth()->id());
        // });

        return $data;
    }

    /**
     * Configure the table component.
     */
    public function configure(): void
    {
        // Set the primary key for the table.
        $this->setPrimaryKey('id');

        // Set additional selects for the table.
        $this->setAdditionalSelects('pinstance_type', 'user_type');

        // Set the table wrapper attributes.
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        // Set attributes for table header columns.
        $this->setThAttributes(function (Column $column) {
            // Check if the column title is 'Tax Types'.
            if ($column->getTitle() == 'Tax Types') {
                // Return the style attribute for the column with a width of 20%.
                return [
                    'style' => 'width: 20%;',
                ];
            }
            // Return an empty array if the column title is not 'Tax Types'.
            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make('user_type', 'user_id')->hideIf(true),
            Column::make('ZTN No')
                ->label(fn ($row) => $row->pinstance->business->ztn_number ?? ''),
            Column::make('TIN')
                ->label(fn ($row) => $row->pinstance->business->tin ?? ''),
            Column::make('Business Name')
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
            Column::make('Filled On', 'created_at')
                ->format(fn ($value) => Carbon::create($value)->format('d-m-Y')),
            Column::make('Action', 'pinstance_id')
                ->view('audit.approval.action')
                ->html(true),

        ];
    }
}
