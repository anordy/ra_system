<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\ReliefProject;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ReliefProjectTable extends DataTableComponent
{
    use LivewireAlert;

    public function builder(): Builder
    {
        return ReliefProject::query();
    }

    protected $listeners = [
        'confirmed',
        'toggleStatus',
    ];

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
            Column::make("Name", "name")
                ->sortable(),
            Column::make("Description", "description")
                ->sortable(),
            Column::make('Configure Projects')
                ->label(fn ($row) => view('relief.project.includes.project-actions', compact('row'))),
            Column::make("Actions", "id")->view("relief.project.includes.actions"),
        ];
    }

    public function delete($id)
    {
        $projectSection = ReliefProject::find(decrypt($id));
        //check if ministry has been used in relief project list and if so, prevent deletion
        if ($projectSection->reliefProjects()->count() > 0) {
            $this->alert('error', 'Cannot delete project section. Project section is used in Project.');
        } else {
            $projectSection->delete();
            $this->alert('success', 'Project Section deleted successfully.');
        }
    }
}
