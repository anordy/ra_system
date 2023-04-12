<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\ReliefMinistry;
use App\Models\Relief\ReliefProjectList;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Support\Facades\Gate;

class ReliefMinistriesTable extends DataTableComponent
{
    use CustomAlert;

    public function builder(): Builder
    {
        return ReliefMinistry::query();
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
            Column::make("Type", "type")
                ->sortable(),
            Column::make("Actions", "id")->view("relief.ministries.includes.actions"),
        ];
    }

    public function delete($id)
    {
        if(!Gate::allows('relief-ministries-delete')){
            abort(403);
        }
        $ministries = ReliefMinistry::find($id);
        //check if ministry has been used in relief project list and if so, prevent deletion
        if ($ministries->projectList()->count()>0) {
            $this->customAlert('error', 'Cannot delete ministry. Ministry is used in project.');
        } else {
            $ministries->delete();
            $this->customAlert('success', 'Ministry deleted successfully.');
        }
    }
}
