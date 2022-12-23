<?php

namespace App\Http\Livewire\Settings\ApprovalLevel;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\ApprovalLevel;

class ApprovalLevelTable extends DataTableComponent
{
    protected $model = ApprovalLevel::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Name", "name")
                ->sortable()->searchable(),
            Column::make("Level", "level")
                ->sortable()->searchable(),
            Column::make("Details", "details")
                ->sortable()->searchable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
