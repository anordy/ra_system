<?php

namespace App\Http\Livewire\NonTaxResident\Returns;

use App\Enum\CustomMessage;
use App\Enum\GeneralConstant;
use App\Models\FinancialMonth;
use App\Models\Ntr\NtrBusiness;
use App\Traits\CustomAlert;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class NonFilersTable extends DataTableComponent
{
    use CustomAlert;

    public $financialMonthId, $financialMonths = [];


    public function filters(): array
    {
        $this->financialMonths = $this->getFinancialMonths(now()->format('Y'));

        $years = [
            now()->format('Y') => now()->format('Y'),
            now()->subYear()->format('Y') => now()->subYear()->format('Y'),
        ];

        return [
            SelectFilter::make('Financial Year')
                ->options($years)
                ->filter(function (Builder $builder, string $value) {
                    $this->financialMonths = $this->getFinancialMonths((int)$value);
                }),
            SelectFilter::make('Financial Month')
                ->options($this->financialMonths)
                ->filter(function (Builder $builder, string $value) {
                    $this->financialMonthId = (int)$value;
                    $this->emit('refreshDatatable');
                }),
        ];
    }

    public function builder(): Builder
    {
        return NtrBusiness::query()
            ->select([
                'ntr_businesses.name as business_name',
                'ntr_businesses.email as business_email',
                'ntr_businesses.ztn_location_number as location_number',
                'ntr_businesses.vrn as vrn',
            ])
            ->join('ntr_business_tax_types', 'ntr_business_tax_types.ntr_business_id', '=', 'ntr_businesses.id')
            ->leftJoin('ntr_electronic_vat_returns', function ($join) {
                $join->on('ntr_electronic_vat_returns.business_id', '=', 'ntr_businesses.id')
                    ->where('ntr_electronic_vat_returns.financial_month_id', '=', $this->financialMonthId);
            })
            ->whereNull('ntr_electronic_vat_returns.business_id')
            ->orderBy('ntr_businesses.name', 'asc');
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
            Column::make('Name', 'name')
                ->searchable(),
            Column::make('Email', 'email'),
            Column::make('Location Number', 'ztn_location_number')
                ->searchable(),
            Column::make('Action', 'id')->view('non-tax-resident.business.includes.actions'),
        ];
    }

    private function getFinancialMonths($year)
    {
        try {
            return FinancialMonth::query()
                ->select('id', 'name')
                ->wherehas('year', function ($query) use ($year) {
                    $query->where('code', $year);
                })
                ->get()
                ->keyBy('id')
                ->map(fn($month) => $month->name)
                ->toArray();
        } catch (\Exception $exception) {
            Log::error('NON-TAX-RESIDENT-RETURNS-NON-FILERS-GET-FINANCIAL-MONTHS', [$exception]);
            $this->customAlert(GeneralConstant::ERROR, CustomMessage::ERROR);
        }
    }
}
