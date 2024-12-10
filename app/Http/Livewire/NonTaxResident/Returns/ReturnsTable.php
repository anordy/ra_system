<?php

namespace App\Http\Livewire\NonTaxResident\Returns;

use App\Enum\GeneralConstant;
use App\Models\Ntr\Returns\NtrVatReturn;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ReturnsTable extends DataTableComponent
{
    public $status;

    public function mount($status) {
        $this->status = $status;
    }

    public function builder(): Builder
    {
        return NtrVatReturn::where('ntr_electronic_vat_returns.status', $this->status)
            ->orderBy('ntr_electronic_vat_returns.created_at', 'desc');
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
            Column::make('Name', "business.name")
                ->searchable(),
            Column::make('ZTN', "business.ztn_location_number"),
            Column::make('VRN', "business.vrn"),
            Column::make('Filing Month', "month.name"),
            Column::make('Financial Year', "year.name"),
            Column::make('Currency', "currency"),
            Column::make('Taxable Amount', "total_amount_due_with_penalties")
                ->format(function ($value) {
                    return number_format($value ?? GeneralConstant::ZERO_INT, 2);
                }),
            Column::make('Filed Date', "created_at")
                ->format(function ($value) {
                    if ($value) {
                        return Carbon::create($value)->format('d M, Y');
                    }
                    return 'N/A';
                }),
            Column::make('Status', 'payment_status')->view('returns.includes.payment-status'),
            Column::make('Action', 'id')->view('non-tax-resident.returns.includes.actions'),
        ];
    }



}
