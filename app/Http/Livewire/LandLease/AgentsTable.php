<?php

namespace App\Http\Livewire\LandLease;

use App\Models\LandLeaseAgent;
use App\Models\Taxpayer;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AgentsTable extends DataTableComponent
{
    use LivewireAlert;

    public function builder(): Builder
    {
        return LandLeaseAgent::query()->with('taxpayer');
    }

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
            Column::make("Agent Number", "agent_number")
                ->sortable(),
            Column::make("Name", "taxpayer_id")
                ->format(fn($value, $row) => $row->taxpayer->fullname())
                ->sortable(),
            Column::make("ZRB Ref No.", "taxpayer.reference_no")
                ->format(function ($value, $row) {
                    return $row->taxpayer->reference_no;
                })
                ->sortable(),
            Column::make("Phone Numbers", "taxpayer.mobile")
                ->format(function ($value, $row) {
                    return $row->taxpayer->mobile ?? 'N/A' . ' / ' . $row->taxpayer->alt_mobile ?? 'N/A';
                })
                ->sortable(),
            Column::make("email", "taxpayer.email")
                ->sortable(),
            Column::make("Reg. Date", "created_at")
                ->format(function ($value) {
                    return date('d/m/Y', strtotime($value));
                })
                ->sortable(),
            Column::make("Status", "status")
                ->view("land-lease.includes.agent-status"),
            Column::make('Action', 'id')
                ->view("land-lease.includes.agent-actions"),
        ];
    }

}
