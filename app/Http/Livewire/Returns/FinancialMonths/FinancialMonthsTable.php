<?php

namespace App\Http\Livewire\Returns\FinancialMonths;

use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class FinancialMonthsTable extends DataTableComponent
{

    public $today, $canDualControl;
    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['financial_year_id']);

    }

    public function builder(): Builder
    {
        $this->canDualControl = approvalLevel(Auth::user()->level_id, 'Maker');
        $day = date('Y-m-d');
        $day = date('F', strtotime($day));
        $year = FinancialYear::select('id')->where('code',date('Y'))->first();
        if (is_null($year)){
            abort(404, 'Financial year not found.');
        }
        $today = FinancialMonth::query()->select('id')->where('financial_year_id', $year->id)->where('name', $day)->first();
        if (is_null($today)){
            abort(404, 'Financial year not found');
        }
        $this->today = $today->id;
        return FinancialMonth::query()->orderBy('financial_months.id', 'desc');
    }


    public function columns(): array
    {

        return [
            Column::make("Month", "name")
                ->sortable()->searchable(),
            Column::make("year", "year.code")
                ->sortable()->searchable(),
            Column::make("Normal Due Date", "due_date")
                ->sortable()->searchable(),
            Column::make("Lumpsum Due Date", "lumpsum_due_date")
                ->sortable()->searchable(),
            Column::make("Created At", "created_at")
                ->sortable()->searchable(),

            Column::make('Approval Status', 'is_approved')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<< HTML
                            <span class="badge badge-warning p-2 rounded-0" >Not Approved</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<< HTML
                            <span class="badge badge-success p-2 rounded-0" >Approved</span>
                        HTML;
                    }
                    elseif ($value == 2) {
                        return <<< HTML
                            <span class="badge badge-danger p-2 rounded-0" >Rejected</span>
                        HTML;
                    }

                })->html(),
            Column::make('Edit Status', 'is_updated')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<<HTML
                            <span class="badge badge-warning p-2 rounded-0" >Not Updated</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<<HTML
                            <span class="badge badge-success p-2 rounded-0" >Updated</span>
                        HTML;
                    }
                })
                ->html(),

            Column::make('Action', 'id')
                ->format(function ($value, $row) {
                    $edit = '';
                    $extend = '';
                    $value = "'".encrypt($value)."'";
                    if (Gate::allows('setting-user-edit') && $this->canDualControl) {
                        $edit = <<< HTML
                                    <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'returns.financial-months.edit-modal',$value)"><i class="bi bi-pencil-square"></i> </button>
                                HTML;
                    }
                    if ($this->today == $value){
                        $extend = <<< HTML
                    <button class="btn btn-success btn-sm" onclick="Livewire.emit('showModal', 'returns.financial-months.extend-month-modal',$value)"><i class="bi bi-pencil-square mr-1"></i>Extend</button>
                HTML;}
                    return $edit.$extend;
                })
                ->html(true),
        ];
    }
}
