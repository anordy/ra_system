<?php

namespace App\Http\Livewire\PropertyTax\Payments;

use App\Enum\PropertyStatus;
use App\Models\FinancialYear;
use App\Models\PropertyTax\Property;
use App\Traits\CustomAlert;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class NonBillsTable extends DataTableComponent
{
    use CustomAlert;

    public function builder(): Builder
    {
        return Property::where('status', PropertyStatus::APPROVED)
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
            Column::make('URN', 'urn')
                ->searchable()
                ->format(function ($value, $row) {
                    return $value ?? 'N/A';
                }),
            Column::make('Type', 'type')
                ->searchable()
                ->format(function ($value, $row) {
                    return formatEnum($value) ?? 'N/A';
                }),
            Column::make('Usage Type', 'usage_type')
                ->searchable()
                ->format(function ($value, $row) {
                    return formatEnum($value) ?? 'N/A';
                }),
            Column::make('Region', 'region_id')
                ->searchable()
                ->format(function ($value, $row) {
                    return $value ?? 'N/A';
                }),
            Column::make('District', 'district_id')
                ->searchable()
                ->format(function ($value, $row) {
                    return $value ?? 'N/A';
                }),
            Column::make('Ward', 'ward_id')
                ->searchable()
                ->format(function ($value, $row) {
                    return $value ?? 'N/A';
                }),
            Column::make('Responsible Person', 'taxpayer_id')
                ->format(function ($value, $row) {
                    return $row->taxpayer->fullname() ?? 'N/A';
                }),
            Column::make('Date of Registration', 'created_at')
                ->format(function ($value, $row) {
                    return $row->created_at->toFormattedDateString() ?? 'N/A';
                }),
            Column::make('Action', 'id')
                ->view('property-tax.includes.actions'),
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
                    $builder->whereDoesntHave('payments', function ($query) use ($value) {
                        $query->where('financial_year_id', $value);
                    });
                }),
        ];
    }

}
