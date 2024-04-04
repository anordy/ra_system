<?php

namespace App\Http\Livewire\PublicService;

use App\Models\PublicService\PublicServiceReturn;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PublicServicePaymentsTable extends DataTableComponent
{

    public $motorId;

    public function builder(): Builder
    {
        return PublicServiceReturn::query();
    }

    public function columns(): array
    {
        return [
            Column::make(__('Business Name'), 'business.name')
                ->searchable(),
            Column::make(__('Plate Number'), 'motor.mvr.plate_number')
                ->searchable(),
            Column::make(__('Registration Type'), 'motor.mvr.regtype.name')
                ->searchable(),
            Column::make(__('Currency'), 'currency')
                ->searchable(),
            Column::make(__('Amount'), 'amount')
                ->format(function ($value, $row) {
                    return number_format($value ?? 0, 2);
                }),
            Column::make(__('Period (Month)'), 'payment_months')
                ->searchable(),
            Column::make(__('Start Date'), 'start_date')
                ->format(function ($value, $row) {
                    return Carbon::create($value)->format('d M Y');
                }),
            Column::make(__('End Date'), 'end_date')
                ->format(function ($value, $row) {
                    return Carbon::create($value)->format('d M Y');
                }),
            Column::make('Status', 'payment_status')
                ->view('public-service.payments.includes.payment-status'),
            Column::make('Actions', 'id')
                ->view('public-service.payments.includes.actions'),
        ];
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            return [];
        });
    }
}