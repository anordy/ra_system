<?php

namespace App\Http\Livewire\Returns\LumpSum;

use App\Models\Returns\LumpSum\LumpSumReturn;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LumpSumReturnsTable extends DataTableComponent
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
        return LumpSumReturn::query()->where('filed_by_id', auth()->user()->id);
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Financial Year', 'financialyear.name')
                ->sortable()
                ->searchable(),
            Column::make('Quarter', 'quarter')
            ->view('returns.lump-sum.quater')
                ->searchable(),
            Column::make('Amount', 'total_amount_due_with_penalties')
                ->sortable()
                ->searchable(),
            Column::make('Control No', 'control_no')
                ->searchable()
                ->sortable(),
            Column::make('Status', 'status')
            ->view('returns.lump-sum.status')
                ->searchable()
                ->sortable(),
            Column::make('Action', 'id')
                ->view('returns.lump-sum.actions'),
        ];
    }
}
