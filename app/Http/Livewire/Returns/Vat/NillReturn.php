<?php

namespace App\Http\Livewire\Returns\Vat;

use App\Models\Returns\Vat\VatReturn;
use App\Models\Returns\Vat\VatReturnConfig;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NillReturn extends Component
{
    public function mount()
    {
        $returnAll = VatReturn::all();
        $initial = $returnAll->first()->financial_month_id;
        $final = $returnAll->last()->financial_month_id;
//        $return=[];
//        for ($x=$initial; $x <= $final; $x = $x +1 )
//        {
//            $return[] = VatReturn::query()
//                ->where('infrastructure_tax',1399200.00)
//                ->where('total_amount_due_with_penalties',3592014.76)
//                ->whereIn('financial_month_id',[$x, $x+1, $x + 2])
//                ->get();
//    }

        $return = DB::table('vat_returns')
            ->where('total_amount_due','=',0)
            ->orderByDesc('id')->limit(3)->get();
        dd($return);

    }
    public function render()
    {
        return view('livewire.returns.vat.nill-return');
    }
}
