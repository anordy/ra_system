<?php

namespace App\Services;

use App\Models\Returns\TaxReturn;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FixBillExpirery
{

   public function run($id, $due_date) {

       $taxReturn = TaxReturn::where('id', $id)->first();

           try {
               DB::beginTransaction();

               $taxReturn->update([
                   'curr_payment_due_date' => Carbon::create($due_date)->endOfDay()
               ]);

               foreach ($taxReturn->bills as $bill) {
                   $bill->bill_items()->delete();
               }

               $taxReturn->bills()->delete();


               DB::commit();
           } catch (\Exception $exception) {
               DB::rollBack();
               throw $exception;
           }

   }

}