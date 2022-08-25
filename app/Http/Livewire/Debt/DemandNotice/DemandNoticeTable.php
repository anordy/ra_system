<?php

namespace App\Http\Livewire\Debt\DemandNotice;

use App\Models\Debts\SentDemandNotice;
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
        $this->debtId = $debtId;
    }

    public function builder(): Builder
    {
        return SentDemandNotice::where('debt_id', $this->debtId)->orderBy('sent_demand_notices.created_at', 'desc');
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
            Column::make('Sent at', 'created_at')
                ->format(function ($value, $row) {
                    return Carbon::create($value)->format('d M Y');
                }),
        ];
    }
}
