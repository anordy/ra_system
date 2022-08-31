<?php

namespace App\Http\Livewire\Returns\LumpSum;

use App\Models\Returns\LumpSum\LumpSumReturn;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LumpSumDebtReturnsTable extends DataTableComponent
{
    use LivewireAlert;

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
    }

    public function builder(): Builder
    {
        return LumpSumReturn::query()
        ->whereHas('debt')
        ->orderBy('lump_sum_returns.created_at', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Branch / Location', 'businessLocation.name')
            ->sortable()
            ->searchable(),
            Column::make('TIN', 'business.tin')
                ->sortable()
                ->searchable(),
            Column::make('Financial Year', 'financialyear.name')
                ->sortable()
                ->searchable(),
            Column::make('Quarter Of', 'quarter_name')
               ->sortable()
                ->searchable(),
            Column::make('Amount', 'total_amount_due_with_penalties')
                ->sortable()
                ->searchable(),
            Column::make('Control No', 'id')
            ->label(fn ($row) => $row->bill->control_number ?? $row->debt->bill->control_number ?? '')
            ->searchable(),
            Column::make('Status', 'status')
            ->view('returns.lump-sum.status')
                ->searchable()
                ->sortable(),
            Column::make('Action', 'id')
                ->view('returns.lump-sum.actions'),
        ];
    }
}
