<?php

namespace App\Http\Livewire\Debt\Offence;

use App\Enum\ReturnCategory;
use App\Models\Returns\TaxReturn;
use App\Models\ZmBill;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Offence\Offence as OffenceModal;

class Offence extends Component
{
//    public $offences = [];
//
//    public function mount($offences)
//    {
//        $this->$offences = $offences->toArray() ?? [];
//    }

    public function render()
    {
        $offences = OffenceModal::with('taxTypes')->get();
//        dd($offences);
        return view('livewire.offence.offence',compact('offences'));
    }

//
//    public function builder(): Builder
//    {
//        return \App\Models\Offence\Offence::query()
//            ->orderBy('created_at', 'desc');
//    }
//
//    public function configure(): void
//    {
//        $this->setPrimaryKey('id');
//        $this->setTableWrapperAttributes([
//            'default' => true,
//            'class' => 'table-bordered table-sm',
//        ]);
////        $this->setAdditionalSelects(['tax_returns.business_id', 'tax_type_id', 'location_id', 'financial_month_id']);
//    }
//
//    public function columns(): array
//    {
//        return [
//
//            Column::make('Debtor Name', 'name')
//                ->sortable()
//                ->searchable(),
//            Column::make('Mobile', 'mobile')
//                ->sortable()
//                ->searchable(),
//            Column::make('Amount', 'amount')
//                ->format(function ($value, $row) {
//                    return number_format($row->amount, 2);
//                }),
//            Column::make('Currency', 'currency')
//                ->sortable()
//                ->searchable(),
//            Column::make('Tax Type', 'tax_type')
//                ->sortable()
//                ->searchable(),
//            Column::make('Action', 'id')->view('debts.includes.actions'),
//        ];
//    }
}
