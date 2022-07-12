<?php

namespace App\Http\Livewire\Business;

use App\Models\BranchStatus;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\BusinessLocation;

class BranchesTable extends DataTableComponent
{
    protected $model = BusinessLocation::class;

    public function builder(): Builder
    {
        return BusinessLocation::where('status', BranchStatus::PENDING)->orWhere('status', BranchStatus::REJECTED);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make('Status'),
            Column::make('Action', 'status'),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
