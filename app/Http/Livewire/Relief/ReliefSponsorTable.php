<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\ReliefSponsor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ReliefSponsorTable extends DataTableComponent
{

    use LivewireAlert;

    public function builder(): Builder
    {
        return ReliefSponsor::query();
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
            Column::make("Acronym", "acronym")
                ->sortable(),
            Column::make("Name", "name")
                ->sortable(),
            Column::make("Description", "description")
                ->sortable(),
            Column::make("Actions", "id")->view("relief.sponsors.includes.actions"),
        ];
    }

    public function delete($id)
    {
        if(!Gate::allows('relief-sponsors-delete')){
            abort(403);
        }
        $sponsors = ReliefSponsor::findOrFail($id);

        if ($sponsors->projectLists()->count()>0) {
            $this->alert('error', 'Cannot delete Sponsor. its used in one of created project.');
        } else {
            $sponsors->delete();
            $this->alert('success', 'Sponor deleted successfully.');
        }
    }

}
