<?php

namespace App\Http\Livewire\Relief;

// use Livewire\Component;
// use App\Models\LandLease;
use App\Models\Relief\Relief;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ReliefReportTable extends DataTableComponent
{
    use LivewireAlert;

    public $dates = [];
    public $relief;

    protected $listeners = ['refreshTable' => 'refreshTable', 'test'];

    public function builder(): Builder
    {

        $dates = $this->dates;
        if ($dates == []) {
            return Relief::query()->orderBy('reliefs.created_at', 'asc');
        }
        if ($dates['startDate'] == null || $dates['endDate'] == null) {
            return Relief::query()->orderBy('reliefs.created_at', 'asc');
        }
        return Relief::query()->whereBetween('reliefs.created_at', [$dates['startDate'], $dates['endDate']])->orderBy('reliefs.created_at', 'asc');
    }

    public function refreshTable($dates)
    {
        //    dd('here');
        $this->dates = $dates;
        $this->emitTo('relief.relief-report-summary', 'refreshSummary', $dates);
        $this->builder();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setAdditionalSelects(['reliefs.id', 'reliefs.project_id', 'reliefs.project_list_id', 'reliefs.location_id', 'reliefs.created_by', 'reliefs.created_at']);
    }

    public function columns(): array
    {
        return [
            Column::make("Project", "project.name")
                ->searchable()
                ->sortable(),
            Column::make("Project Description", "project.description")
                ->searchable()
                ->sortable(),
            Column::make("Project Section", "projectSection.name")
                ->searchable()
                ->sortable(),
            Column::make("VAT amount", "vat_amount")
                ->format(function ($value, $row) {
                    return number_format($value, 1);
                })
                ->searchable()
                ->sortable(),
            Column::make("Relieved amount", "relieved_amount")
                ->format(function ($value, $row) {
                    return number_format($value, 1);
                })
                ->searchable()
                ->sortable(),
            Column::make("Relieved Rate", "rate")
                ->format(function ($value, $row) {
                    return number_format($value, 1).'%';
                })
                ->searchable()
                ->sortable(),
            Column::make("Supplier Name", "business.name")
                ->searchable()
                ->sortable(),
            Column::make("Supplier Location", "location.name")
                ->searchable()
                ->sortable(),
            Column::make("Created By", "created_by")
                ->format(function ($value, $row) {
                    return $row->createdBy->fname . ' ' . $row->createdBy->lname;
                })
                ->searchable()
                ->sortable(),
            Column::make("Created At", "created_at")
                ->format(function ($value, $row) {
                    return date('d/m/Y', strtotime($value));
                })
                ->searchable()
                ->sortable(),

            // Column::make("Actions", "id")->view("land-lease.includes.actions"),
        ];
    }
}
