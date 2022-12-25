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
            Column::make('Approval Status', 'is_approved')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<< HTML
                            <span style="border-radius: 0 !important;" class="badge badge-warning p-2" >Not Approved</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<< HTML
                            <span style="border-radius: 0 !important;" class="badge badge-success p-2" >Approved</span>
                        HTML;
                    }
                    elseif ($value == 2) {
                        return <<< HTML
                            <span style="border-radius: 0 !important;" class="badge badge-danger p-2" >Rejected</span>
                        HTML;
                    }

                })->html(),
        ];
    }
}
