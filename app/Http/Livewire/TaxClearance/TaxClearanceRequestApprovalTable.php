<?php

namespace App\Http\Livewire\TaxClearance;

use App\Enum\TaxClearanceStatus;
use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\TaxClearanceRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxClearanceRequestApprovalTable extends DataTableComponent
{
    public function mount()
    {
        if (!Gate::allows('tax-clearance-view')) {
            abort(403);
        }
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function builder(): Builder
    {
        return TaxClearanceRequest::where('tax_clearance_requests.status', TaxClearanceStatus::REQUESTED)
            ->with('business:name')
            ->with('businessLocation:name');
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Branch', 'businessLocation.name')
                ->format(function ($value, $row) {
                    $column = 'businesslocation.name';
                    return $row->$column;
                })
                ->sortable()
                ->searchable(),
            Column::make('Status', 'status')->view('tax-clearance.includes.status'),
            Column::make('Action', 'id')->view('tax-clearance.includes.actions'),
        ];
    }
}
