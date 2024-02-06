<?php

namespace App\Http\Livewire\PropertyTax;

use App\Enum\PropertyTypeStatus;
use App\Models\PropertyTax\PropertyPayment;
use App\Traits\CustomAlert;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PropertyTaxPaymentTable extends DataTableComponent
{
    use CustomAlert;

    public $propertyId;

    public function mount($propertyId)
    {
        $this->propertyId = decrypt($propertyId);
    }

    public function builder(): Builder
    {
        return PropertyPayment::where('property_id', $this->propertyId)->orderByDesc('created_at');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('Property Name', 'property_id')
                ->searchable()
                ->format(function ($value, $row) {
                    if ($row->type != PropertyTypeStatus::CONDOMINIUM) {
                        return $row->property->name ?? 'N/A';
                    } else {
                        return "{$row->property->name} - {$row->property->unit->name}";
                    }
                }),
            Column::make('Payment Year', 'financial_year_id')
                ->searchable()
                ->format(function ($value, $row) {
                    return $row->year->code;
                }),
            Column::make('Tax Amount', 'amount')
                ->searchable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                }),
            Column::make('Interest', 'interest')
                ->searchable()
                ->format(function ($value, $row) {
                    return number_format($value, 2) ?? 0;
                }),
            Column::make('Total Amount', 'total_amount')
                ->searchable()
                ->format(function ($value, $row) {
                    return number_format($value, 2) ?? 0;
                }),
            Column::make('Due Date', 'curr_payment_date')
                ->format(function ($value, $row) {
                    if (!$value) {
                        return 'N/A';
                    }
                    return $value->toFormattedDateString() ?? 'N/A';
                }),
            Column::make('Date of Payment', 'paid_at')
                ->format(function ($value, $row) {
                    if (!$value) {
                        return 'N/A';
                    }
                    return $value->toFormattedDateString() ?? 'N/A';
                }),
            Column::make('Status', 'payment_status')
                ->view('property-tax.includes.payment-status'),

        ];
    }

}
