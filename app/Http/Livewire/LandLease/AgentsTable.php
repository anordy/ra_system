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
        return LandLeaseAgent::query();
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
            Column::make("Number", "agent_number")
                ->sortable(),
            Column::make("Name", "taxpayer_id")
                ->format(fn($taxpayer_id) => Taxpayer::query()->find($taxpayer_id)->fullname())
                ->sortable(),
            Column::make("Phone Numbers", "taxpayer_id")
                ->format(function ($taxpayer_id) {
                    $taxpayer = Taxpayer::query()->find($taxpayer_id);
                    return $taxpayer->mobile . '/' . $taxpayer->alt_mobile;
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
