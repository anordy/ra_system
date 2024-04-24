<?php

namespace App\Http\Livewire\Returns\Vat;

use App\Models\FinancialMonth;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Returns\Vat\VatReturnConfig;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NillReturn extends Component
{
    public function mount()
    {

    }

    public function nilTotal()
    {
        $data  =  DB::table('vat_returns')
            ->orderByDesc('id')
            ->get();

        $array_data  =array();

        $first_data  = $data[0];
        $error  =  false;
        foreach ($data as $index=>$row){
            $r  =[];

            if ($index>=0){
                if ($row->total_amount_due==0){
                    if (!$error){
                        $r['data']=$row;
                        $r['id']=$row->id;
                        array_push($array_data,$r);
                        continue;
                    }
                }
                else{
                    if (count($array_data)<=2){
                        $array_data = array();
                        continue;
                    }{
                        $error  = true;
                        continue;
                    }
                }

            }else{
                if ($row->total_amount_due==0){
                    $r['data']=$row;
                    $r['id']=$row->id;
                    array_push($array_data,$r);
                }
            }


        }

        return response()->json($array_data);
    }
    public function render()
    {
        return view('livewire.returns.vat.nill-return');
    }
}
