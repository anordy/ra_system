<?php

namespace App\Http\Livewire\Payments;

use App\Enum\GeneralConstant;
use App\Models\PBZStatement;
use App\Models\PBZTransaction;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PBZStatementsTable extends DataTableComponent
{
    use CustomAlert;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];

    public $data = [];

    public function filterData($data)
    {
        $this->data = $data;
        $this->emit('$refresh');
    }

    public function builder(): Builder
    {
        $data   = $this->data;
        $query = (new PBZStatement())->newQuery();

        if (isset($data['currency']) && $data['currency'] != GeneralConstant::ALL) {
            $query->where('currency', $data['currency']);
        }

        if (isset($data['range_start']) && isset($data['range_end'])) {
            $query->whereBetween('stmdt', [$data['range_start'], $data['range_end']]);
        } else {
            $query->whereBetween('stmdt', [Carbon::now()->startOfMonth()->toDateString(), Carbon::now()->toDateString()]);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setPerPageAccepted([15, 25, 50, 100]);
    }

    public function columns(): array
    {
        return [
            Column::make('Account No.', 'account_no')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return $value ? $value : 'N/A';
                }),
            Column::make('Account Name', 'account_name')
                ->sortable()
                ->searchable()
                ->format(fn($value) => $value ?? 'N/A'),
            Column::make('Currency', 'currency')
                ->sortable()
                ->searchable()
                ->format(fn($value) => $value ?? 'N/A'),

            Column::make('Statement Date', 'stmdt')
                ->format(function ($value, $row) {
                    return $value ? Carbon::make($value)->toDateString() : 'N/A';
                }),
            Column::make('Generated At', 'credttm')
                ->format(function ($value, $row) {
                    return $value ? Carbon::make($value)->toDateString() : 'N/A';
                }),
            Column::make('No. of Transactions', 'nboftxs')
                ->format(function ($value, $row) {
                    return number_format($value, 0);
                }),
            Column::make('Opening Balance', 'openbal')
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                }),
            Column::make('Closing Balance', 'closebal')
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                }),
            Column::make('Status', 'status')
                ->view('payments.pbz.includes.statement-status'),
            Column::make('Actions', 'id')
                ->view('payments.pbz.includes.statement-actions')
        ];
    }
}
