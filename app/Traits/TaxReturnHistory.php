<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\Returns\TaxReturnHistory as ReturnsTaxReturnHistory;

trait TaxReturnHistory
{

    public function saveHistory($tax_return)
    {
        try {
            $history = ReturnsTaxReturnHistory::where('tax_return_id', $tax_return->id)->latest()->first();

            $version = 1;

            if ($history) {
                $version = $history->version + 1;
            }

            ReturnsTaxReturnHistory::create([
                'tax_return_id' => $tax_return->id,
                'return_info' => json_encode($tax_return),
                'penalties' => json_encode($tax_return->return->penalties),
                'return_items' => json_encode($tax_return->return->items),
                'version' => $version
            ]);
        } catch (Exception $e) {
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw new Exception("Something Went Wrong");
        }
    }
}
