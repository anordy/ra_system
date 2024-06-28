<?php

namespace App\Http\Livewire\Returns\SevenDaysFinancialMonths;

use App\Enum\GeneralConstant;
use App\Models\FinancialYear;
use App\Models\SevenDaysFinancialMonth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class SevenDaysFinancialMonthsTable extends DataTableComponent
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
        $year = FinancialYear::query()->select('id')->where('code', date('Y'))->first();
        if (is_null($year)){
            abort(404, 'Financial year not found');
        }
        $today = SevenDaysFinancialMonth::query()->select('id')->where('financial_year_id', $year->id)->where('name', $day)->first();
        if (is_null($today)){
            abort(404, 'Financial year not found');
        }
        $this->today = $today->id;
        return SevenDaysFinancialMonth::query()->orderBy('seven_days_financial_months.id', 'desc');
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
            Column::make("Created At", "created_at")
                ->sortable()->searchable(),
            Column::make('Approval Status', 'is_approved')
                ->format(function ($value, $row) {
                    if ($value == GeneralConstant::ZERO_INT) {
                        return <<< HTML
                            <span class="badge badge-warning p-2" >Not Approved</span>
                        HTML;
                    } elseif ($value == GeneralConstant::ONE_INT) {
                        return <<< HTML
                            <span class="badge badge-success p-2" >Approved</span>
                        HTML;
                    }
                    elseif ($value == GeneralConstant::TWO_INT) {
                        return <<< HTML
                            <span  class="badge badge-danger p-2" >Rejected</span>
                        HTML;
                    }

                })->html(),
            Column::make('Edit Status', 'is_updated')
                ->format(function ($value, $row) {
                    if ($value == GeneralConstant::ZERO_INT) {
                        return <<<HTML
                            <span  class="badge badge-warning p-2" >Not Updated</span>
                        HTML;
                    } elseif ($value == GeneralConstant::ONE_INT) {
                        return <<<HTML
                            <span  class="badge badge-success p-2" >Updated</span>
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
                                    <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'returns.seven-days-financial-months.edit-modal',$value)"><i class="bi bi-pencil-square"></i> </button>
                                HTML;
                    }
                    if ($this->today == $value && approvalLevel(Auth::user()->level_id, 'Maker')) {
                        $extend = <<< HTML
                    <button class="btn btn-success btn-sm" onclick="Livewire.emit('showModal', 'returns.seven-days-financial-months.extend-month-modal',$value)"><i class="bi bi-pencil-square mr-1"></i>Extend</button>
                HTML;
                    }
                    return $edit . $extend;
                })
                ->html(true),
        ];
    }
}
