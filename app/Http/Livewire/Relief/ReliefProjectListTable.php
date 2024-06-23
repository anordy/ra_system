<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\ReliefProjectList;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ReliefProjectListTable extends DataTableComponent
{
    use CustomAlert;

    public $projectSectionId;

    public function mount($id)
    {
        $this->projectSectionId = decrypt($id);
    }

    public function builder(): Builder
    {
        return ReliefProjectList::query()
        ->with('ministry')
        ->with('sponsor')
        ->where('project_id', $this->projectSectionId);
    }

    protected $listeners = [
        'confirmed',
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
            Column::make("Rate", "rate")
                ->sortable(),
            Column::make("Ministry", "ministry.name")
                ->sortable()
                ->format(
                    function($value){
                        return $value ? $value : '-';
                    }
                ),
            Column::make("Sponsor", "sponsor.acronym")
                ->sortable()
                ->format(
                    function($value){
                        return $value ? $value : '-';
                    }
                ),
            // Column::make('Action', 'id')
            //     ->format(fn ($value) => <<< HTML
            //         <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'relief.relief-project-list-edit-modal',$value)"><i class="bi bi-pencil-square"></i> </button>
            //         <button class="btn btn-danger btn-sm" wire:click="delete($value)"><i class="bi bi-trash-fill"></i> </button>
            //     HTML)
            //     ->html(true),
            Column::make("Actions", "id")->view("relief.project_list.includes.actions"),
        ];
    }

    public function delete($id)
    {
        $projectList = ReliefProjectList::findOrFail($id);
        //check if ministry has been used in relief project list and if so, prevent deletion
        if ($projectList->reliefs()->count()>0) {
            $this->customAlert('error', 'Cannot delete project. Project is used in Relief.');
        } else {
            $projectList->delete();
            $this->customAlert('success', 'Project deleted successfully.');
        }
    }
}
