<?php

namespace App\Http\Livewire\Settings\DualControlActivity;

use App\Traits\DualControlActivityTrait;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\DualControl;

class ActivityTable extends DataTableComponent
{
    use LivewireAlert, DualControlActivityTrait;

    protected $model = DualControl::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        return DualControl::where('status', 'pending')->orderByDesc('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Module", "controllable_type")
                ->sortable()->format(function ($value){
                    return $this->getModule($value);
                }),
            Column::make("Action Type", "action_detail")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make('Status', 'status')
                ->view('settings.dual-control-activities.includes.status'),
            Column::make("Action", "id")
                ->view('settings.dual-control-activities.includes.actions'),

        ];
    }
}
