<?php

namespace App\Http\Livewire\Debt\Waiver;

use App\Models\WaiverStatus;
use App\Models\Debts\DebtWaiver;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class DebtWaiverTable extends DataTableComponent
{
    use CustomAlert;

    public function mount()
    {
    }
    public function builder(): Builder
    {
        return DebtWaiver::query()
        ->where('debt_waivers.status', WaiverStatus::APPROVED)
        ->orderBy('debt_waivers.created_at', 'desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['debt_type']);

    }

    public function columns(): array
    {
        return [
            Column::make('debt_id', 'debt_id')->hideIf(true),
            Column::make('ZIN', 'debt')
                ->sortable()
                ->searchable()
                ->label(fn ($row) => $row->debt->location->zin),
            Column::make("Business Name", "debt")
                ->label(fn ($row) => $row->debt->business->name),
            Column::make('Location', 'debt')
                ->label(fn ($row) => $row->debt->location->name),
            Column::make('Tax Type', 'debt')
                ->label(fn ($row) => $row->debt->taxtype->name),
            Column::make('Waiver Category', 'category')
                ->format(function ($value, $row) {
                    if ($value === 'interest') {
                        return 'Interest';
                    } else if ($value === 'penalty') {
                        return 'Penalty';
                    } else {
                        return 'Penalty & Interest';
                    }
                }),
            Column::make('Requested On', 'created_at')
                ->format(fn ($value, $row) => $value),
            Column::make('Department', 'debt')
                ->sortable()
                ->searchable()
                ->label(fn ($row) => $row->debt->location->taxRegion->departments->name),
            Column::make('Status', 'status')
                ->view('debts.waivers.includes.status'),
            Column::make('Action', 'id')
                ->view('debts.waivers.includes.action'),
        ];
    }
}
