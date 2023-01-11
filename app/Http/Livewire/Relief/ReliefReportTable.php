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
    public $parameters = [];

    protected $listeners = ['refreshTable' => 'refreshTable', 'test'];

    public function mount($payload)
    {
        $data = json_decode(decrypt($payload), true);
        $this->dates = $data['dates'];
        $this->parameters = $data['parameters'];
    }
    public function builder(): Builder
    {
        $dates = $this->dates;
        $parameters = $this->parameters;
        if ($dates == []) {
            $relief = Relief::query()->orderBy('reliefs.created_at', 'desc');
        } elseif ($dates['startDate'] == null || $dates['endDate'] == null) {
            $relief = Relief::query()->orderBy('reliefs.created_at', 'desc');
        } else {
            $relief = Relief::query()->whereBetween('reliefs.created_at', [$dates['startDate'], $dates['endDate']])->orderBy('reliefs.created_at', 'asc');
        }

        if ($parameters['reportType'] == 'project') {
            if ($parameters['sectionId'] == 'all') {
                $relief->whereNotNull('reliefs.project_id');
            } else {
                $relief->where('reliefs.project_id', $parameters['sectionId']);
                if ($parameters['projectId'] == 'all') {
                    $relief->where('reliefs.project_id', $parameters['sectionId'])
                        ->whereNotNull('reliefs.project_list_id');
                } else {
                    $relief->where('reliefs.project_id', $parameters['sectionId'])
                        ->where('reliefs.project_list_id', $parameters['projectId']);
                }
            }
        } elseif ($parameters['reportType'] == 'supplier') {
            if ($parameters['supplierId'] == 'all') {
                $relief->whereNotNull('reliefs.business_id');
            } else {
                $relief->where('reliefs.business_id', $parameters['supplierId']);
                if ($parameters['locationId'] == 'all') {
                    $relief->where('reliefs.business_id', $parameters['supplierId'])
                        ->whereNotNull('reliefs.location_id');
                } else {
                    $relief->where('reliefs.business_id', $parameters['supplierId'])
                        ->where('reliefs.location_id', $parameters['locationId']);
                }
            }
        } elseif ($parameters['reportType'] == 'sponsor') {
            if ($parameters['id'] == 'all') {
                $relief->whereHas('project', function (Builder $query) {
                    $query->whereNotNull('relief_sponsor_id');
                });
            } elseif ($parameters['id'] == 'without') {
                $relief->whereHas('project', function (Builder $query) {
                    $query->whereNull('relief_sponsor_id');
                });
            } else {
                $relief->whereHas('project', function (Builder $query) use ($parameters) {
                    $query->where('relief_sponsor_id', $parameters['id']);
                });
            }
        } elseif ($parameters['reportType'] == 'ministry') {
            if ($parameters['id'] == 'all') {
                $relief->whereHas('project', function (Builder $query) {
                    $query->whereNotNull('ministry_id');
                });
            } elseif ($parameters['id'] == 'without') {
                $relief->whereHas('project', function (Builder $query) {
                    $query->whereNull('ministry_id');
                });
            } else {
                $relief->whereHas('project', function (Builder $query) use ($parameters) {
                    $query->where('ministry_id', $parameters['id']);
                });
            }
        }
        return $relief;
    }

    public function refreshTable($dates, $parameters)
    {
        $this->dates = $dates;
        $this->parameters = $parameters;
        $this->builder();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setAdditionalSelects(['reliefs.project_id', 'reliefs.project_list_id', 'reliefs.location_id', 'reliefs.created_by', 'reliefs.created_at']);
    }

    public function columns(): array
    {
        return [
            Column::make("Project", "project.name")
                ->searchable()
                ->sortable(),
            Column::make("Project Description")
                ->label(fn ($row) => $row->project->description ?? '')
                ->sortable(),
            Column::make("Project Section")
                ->label(fn ($row) => $row->projectSection->name ?? ''),
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
                ->format(function ($value) {
                    return number_format($value, 1) . '%';
                })
                ->searchable()
                ->sortable(),
            Column::make("Supplier Name", "business.name")
                ->searchable()
                ->sortable(),
            Column::make("Supplier Location", "location.name")
                ->searchable()
                ->sortable(),
            Column::make("Ministry")
                ->label(fn ($row) => $row->project->ministry->name ?? ''),
            Column::make("Sponsor")
                ->label(fn ($row) => $row->project->sponsor->name ?? ''),
            Column::make("Created By", "created_by")
                ->label(function ($row) {
                    return $row->createdBy->fname . ' ' . $row->createdBy->lname;
                })
                ->searchable()
                ->sortable(),
            Column::make("Created At")
                ->label(function ($row) {
                    return date('d/m/Y', strtotime($row->created_at));
                })
                ->searchable()
                ->sortable(),
        ];
    }

    public function getSponsorName($id)
    {
    }

    public function getMinistry()
    {
    }
}
