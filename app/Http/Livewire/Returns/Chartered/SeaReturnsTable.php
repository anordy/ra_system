<?php

namespace App\Http\Livewire\Returns\Chartered;

use App\Enum\AssistantStatus;
use App\Models\Returns\Chartered\CharteredReturn;
use Carbon\Carbon;
use App\Models\TaxType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class SeaReturnsTable extends DataTableComponent
{

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['financial_month_id', 'business_location_id']);
    }

    public function builder(): Builder
    {
        $tax = TaxType::select('id')->where('code', TaxType::CHARTERED_SEA)->first();

        return CharteredReturn::query()
            ->with('business', 'businessLocation')
            ->where('chartered_returns.tax_type_id', $tax->id)
            ->orderBy('chartered_returns.created_at', 'DESC');
    }


    public function columns(): array
    {
        return [
            Column::make(__('Control Number'))
                ->sortable()
                ->searchable()
                ->label(function ($row) {
                    return $row->tax_return->latestBill->control_number ?? 'N/A';
                }),
            Column::make(__('Business Name'), 'business.name')
                ->sortable()
                ->searchable(),
            Column::make(__('Branch / Location'))
                ->sortable()
                ->searchable()
                ->label(function ($row) {
                    return $row->businessLocation->name ?? $row->company_name;
                }),
            Column::make(__('Total Tax'), 'total_amount_due_with_penalties')
                ->sortable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                })
                ->searchable(),
            Column::make(__('Date'), 'created_at')
                ->sortable()
                ->format(function ($value, $row) {
                    return Carbon::create($value)->format('M d, Y');
                })
                ->searchable(),
            Column::make(__('Vetting Status'), 'vetting_status')->view('returns.vetting-status'),
            Column::make(__('Status'), 'status')->format(function ($value, $row) {
                return view('returns.includes.return-payment-status', ['row' => $row]);
            }),
            Column::make(__('Action'), 'id')->view('returns.chartered.includes.actions'),

        ];
    }
}
