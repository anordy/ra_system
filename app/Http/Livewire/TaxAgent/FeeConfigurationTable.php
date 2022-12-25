<?php

namespace App\Http\Livewire\TaxAgent;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaPaymentConfiguration;

class FeeConfigurationTable extends DataTableComponent
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
        return TaPaymentConfiguration::orderBy('id', 'desc');
    }


    public function columns(): array
    {

        return [
            Column::make("Category", "category")
                ->sortable()->searchable(),
            Column::make("duration", "duration")
                ->format(fn($value) => $value . ' years '),
            Column::make('Amount', 'amount')
                ->format(fn($value) => number_format($value, '2', '.', ','))
                ->html()->searchable(),
            Column::make("Currency", "currency")
                ->sortable()->searchable(),
            Column::make("Nationality", "is_citizen")
                ->view('taxagents.includes.no_of_days'),
        ];
    }
}
