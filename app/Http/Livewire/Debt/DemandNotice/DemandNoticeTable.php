<?php

namespace App\Http\Livewire\Debt\DemandNotice;

use App\Models\Debts\DemandNotice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class DemandNoticeTable extends DataTableComponent
{

    use LivewireAlert;

    public $debtId;

    public function mount($debtId)
    {
        $this->debtId = decrypt($debtId);
    }

    public function builder(): Builder
    {
        return DemandNotice::where('debt_id', $this->debtId)->orderBy('created_at', 'desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['sent_by_id']);
    }

    public function columns(): array
    {
        return [
            Column::make('Sent By Category', 'sent_by')
                ->format(function ($value, $row) {
                    return $row->sent_by;
                }),
            Column::make('Sent By', 'user.fname')
                ->format(function ($value, $row) {
                    return $row->user ? "{$row->user->fname} {$row->user->lname}" : 'N/A';
                }),
            Column::make('Paid Within Days', 'paid_within_days'),
            Column::make('Next Notify Days', 'next_notify_days'),
            Column::make('Next Demand Notice', 'next_notify_date')
                ->format(function ($value, $row) {
                    return Carbon::create($value)->format('d M Y');
                }),
            Column::make('Sent at', 'created_at')
                ->format(function ($value, $row) {
                    return Carbon::create($value)->format('d M Y');
                }),
            Column::make('Actions', 'id')
                ->view('debts.demand-notice.includes.actions')
        ];
    }

    public function showDemandNotice()
    {
    }
}
