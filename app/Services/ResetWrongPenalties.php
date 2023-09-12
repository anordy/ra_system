<?php

namespace App\Services;

use App\Models\Returns\TaxReturn;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OverrideWorkflow
{

   public function resetPenalties() {
       $taxTypes = [18,19];

       $taxReturns = TaxReturn::where('financial_month_id', 764)->whereIn('tax_type_id', $taxTypes)->get();

       foreach ($taxReturns as $taxReturn) {
           try {
               DB::beginTransaction();

               $taxReturn->update([
                   'outstanding_amount' => $taxReturn->outstanding_amount - $taxReturn->latestPenalty->late_payment,
                   'total_amount' => $taxReturn->total_amount - $taxReturn->latestPenalty->late_payment,
                   'penalty' => 0,
                   'curr_payment_due_date' => Carbon::today()->endOfDay()
               ]);

               foreach ($taxReturn->bills as $bill) {
                   $bill->bill_items()->delete();
               }

               $taxReturn->bills()->delete();

               $taxReturn->latestPenalty()->delete();

               DB::commit();
           } catch (\Exception $exception) {
               DB::rollBack();
               throw $exception;
           }

       }
   }

}