<?php

namespace App\Http\Livewire\Business\TaxType;

use App\Models\BranchStatus;
use App\Models\BusinessTaxTypeChange;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxTypeTable extends DataTableComponent
{
    protected $model = BusinessTaxTypeChange::class;
    public $status;

    public function mount($status)
    {
        $this->status = $status;
    }

    public function builder(): Builder
    {
        if ($this->status == BranchStatus::PENDING) {
            return BusinessTaxTypeChange::where('status', BranchStatus::PENDING);
        } else if ($this->status == BranchStatus::APPROVED) {
            return BusinessTaxTypeChange::where('status', BranchStatus::APPROVED);
        } else if ($this->status == BranchStatus::REJECTED) {
            return BusinessTaxTypeChange::where('status', BranchStatus::REJECTED);
        }
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            // Column::make("Business Name", "business.name")
            //     ->sortable(),
            // Column::make("Location", "Street")
            //     ->searchable(),
            // Column::make("Physical Address", "physical_address")
            //     ->searchable(),
            Column::make('Status', 'status')->view('business.branches.includes.status'),
            Column::make('Action', 'id')->view('business.branches.includes.actions'),
        ];
    }
}
