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
    public $status;

    public function mount($status)
    {
        $this->status = $status;
    }

    public function builder(): Builder
    {
        if ($this->status == BranchStatus::PENDING) {
            return BusinessLocation::where('business_locations.status', BranchStatus::PENDING)
                ->orderBy('business_locations.created_at', 'DESC')
                ->with('business');
        } else if ($this->status == BranchStatus::APPROVED) {
            return BusinessLocation::whereIn('business_locations.status', [BranchStatus::APPROVED, BranchStatus::DE_REGISTERED, BranchStatus::TEMP_CLOSED])
                ->orderBy('business_locations.created_at', 'DESC')
                ->with('business');
        } else if ($this->status == BranchStatus::REJECTED) {
            return BusinessLocation::where('business_locations.status', BranchStatus::REJECTED)
                ->orderBy('business_locations.created_at', 'DESC')
                ->with('business');
        }
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['is_headquarter']);
    }

    public function columns(): array
    {
        return [
            Column::make("Business Name", "business.name")
                ->sortable(),
            Column::make("Branch Name", "name")
                ->format(function ($value, $row) {
                    if ($row->is_headquarter === 1) {
                        return "Head Quarters";
                    } else {
                        return "{$row->name}";
                    }
                })->html(true),
            Column::make("Location", "Street")
                ->searchable(),
            Column::make("Physical Address", "physical_address")
                ->searchable(),
            Column::make('Status', 'status')->view('business.branches.includes.status'),
            Column::make('Action', 'id')->view('business.branches.includes.actions'),
        ];
    }
}
