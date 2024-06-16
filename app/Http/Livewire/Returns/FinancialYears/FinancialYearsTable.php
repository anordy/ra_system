<?php

namespace App\Http\Livewire\Returns\FinancialYears;

use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaPaymentConfiguration;

class FinancialYearsTable extends DataTableComponent
{


    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

    }

    public function builder(): Builder
    {
        return FinancialYear::query()->orderBy('id', 'desc');
    }


    public function columns(): array
    {

        return [
            Column::make("Name", "name")
                ->sortable()->searchable(),
            Column::make("Code", "code")
                ->sortable()->searchable(),
            Column::make("Status", "active")
                ->sortable()->searchable()
                ->format(function ($value) {
                    if ($value == '0') {
                        return 'Active';
                    } elseif ($value == 'F') {
                        return 'Inctive';
                    }

                }),
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
            Column::make("Created At", "created_at")
                ->sortable()->searchable(),
        ];
    }
}
