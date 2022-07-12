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
        return BusinessLocation::where('business_locations.status', BranchStatus::PENDING)
            ->orWhere('business_locations.status', BranchStatus::REJECTED)
            ->with('business');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Business Name", "business.name")
                ->sortable(),
            Column::make("Location", "Street")
                ->searchable(),
            Column::make("Physical Address", "physical_address")
                ->searchable(),
            Column::make('Status', 'id')->view('business.branches.includes.status'),
            Column::make('Action', 'id')->view('business.branches.includes.actions'),
        ];
    }
}
