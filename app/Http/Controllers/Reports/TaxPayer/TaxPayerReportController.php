<?php

namespace App\Http\Controllers\Reports\TaxPayer;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use PDF;
class TaxPayerReportController extends Controller
{

    public  function  index(){
        return view('reports.taxpayer.index');
    }

    public function exportTaxpayerReportPdf($fileName)
    {
        $filePath = storage_path('app/public/reports/' . $fileName);
        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            abort(404);
        }
    }

}