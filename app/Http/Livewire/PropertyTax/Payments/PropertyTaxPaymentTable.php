<?php

namespace App\Http\Livewire\PropertyTax\Payments;

use App\Enum\PropertyOwnershipTypeStatus;
use App\Models\FinancialYear;
use App\Models\PropertyTax\PropertyPayment;
use App\Models\Returns\ReturnStatus;
use App\Traits\CustomAlert;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class PropertyTaxPaymentTable extends DataTableComponent
{
    use CustomAlert;

    public $status;

    public function mount($status)
    {
        $this->status = $status;
    }

    public function builder(): Builder
    {
        if ($this->status == ReturnStatus::COMPLETE) {
            return PropertyPayment::where('payment_status',ReturnStatus::COMPLETE)
                ->orderByDesc('created_at');
        }
        return PropertyPayment::where('payment_status', '!=' ,ReturnStatus::COMPLETE)
            ->orderByDesc('created_at');
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
            Column::make('URN #', 'property_id')
                ->searchable()
                ->format(function ($value, $row) {
                    return $row->property->urn ?? 'N/A';
                }),
            Column::make('Responsible', 'currency_id')
                ->searchable()
                ->format(function ($value, $row) {
                    if ($row->property->responsible) {
                        $name = ($row->property->responsible->first_name ?? '') . ' ' . ($row->property->responsible->first_name ?? '');
                    } else if ($row->property->ownership->name == PropertyOwnershipTypeStatus::GOVERNMENT
                        || $row->property->ownership->name == PropertyOwnershipTypeStatus::RELIGIOUS) {
                        $name = $row->property->institution_name ?? 'N/A';
                    } else {
                        $name = $row->property->taxpayer->full_name ?? 'N/A';
                    }
                    return $name . ' - ' . ($row->property->taxpayer->mobile ?? 'N/A');
                }),
            Column::make('Payment Year', 'financial_year_id')
                ->format(function ($value, $row) {
                    return $row->year->code;
                }),
            Column::make('Total Amount', 'total_amount')
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

    public function filters(): array
    {
        $years = FinancialYear::query()
            ->select('id', 'code')
            ->where('code', '>=', 2023)
            ->get()
            ->keyBy('id')
            ->map(fn($year) => $year->code)
            ->toArray();

        return [
            SelectFilter::make('Financial Year')
                ->options($years)
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('financial_year_id', $value);
                }),
        ];
    }


}
