<?php

namespace App\Http\Livewire\Returns\FinancialYears;

use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaPaymentConfiguration;

class FinancialYearsTable extends DataTableComponent
{
//    protected $model = TaPaymentConfiguration::class;

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
            Column::make("Created At", "created_at")
                ->sortable()->searchable(),
        ];
    }
}
