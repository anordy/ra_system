<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(){
        // if (!Gate::allows('managerial-business-report-view')) {
        //     abort(403);
        // }
        return view('reports.index');
    }

    public function preview($parameters){
        // if (!Gate::allows('managerial-business-report-view')) {
        //     abort(403);
        // }
        $parameters = json_decode(decrypt($parameters),true);
        return view('reports.business.preview', compact('parameters'));
    }

    public function exportBusinessesReportPdf($data)
    {
        if (!Gate::allows('managerial-report-pdf')) {
            abort(403);
        }

        try {
            $parameters = json_decode(decrypt($data),true);
            $records = $this->getBusinessBuilder($parameters)->get();

            $optionReportTypes = [
                'Business-Reg-By-Nature' => 'Registered Business By Nature of Business',
                'Business-Reg-By-TaxType' => 'Registered Business By Tax Type',
                'Business-Reg-By-TaxPayer' => 'Registered Business By Tax Payer',
                'Business-Reg-Without-ZNO' => 'Registered Business With No ZITAS Number',
                'All-Business' => 'All Registered Business'
            ];
            $parameters['criteria'] = $optionReportTypes[$parameters['criteria']];

            $pdf = PDF::loadView('exports.business.pdf.business',compact('records', 'parameters'));
            $pdf->setPaper('a4', 'landscape');
            $pdf->setOption(['dpi' => ReportStatus::DPI_150, 'defaultFont' => 'sans-serif']);
            return $pdf->download('Business.pdf');
        } catch (\Exception $exception){
            Log::error($exception);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

    public function exportBusinessesTaxtypeReportPdf($data)
    {
        if (!Gate::allows('managerial-report-pdf')) {
            abort(403);
        }

        try {
            $parameters = json_decode(decrypt($data),true);
            $optionReportTypes = [
                'Business-Reg-By-Nature' => 'Registered Business By Nature of Business',
                'Business-Reg-By-TaxType' => 'Registered Business By Tax Type',
                'Business-Reg-By-TaxPayer' => 'Registered Business By Tax Payer',
                'Business-Reg-Without-ZNO' => 'Registered Business With No ZITAS Number',
                'All-Business' => 'All Registered Business'
            ];
            $parameters['criteria'] = $optionReportTypes[$parameters['criteria']];
            $records = $this->getBusinessBuilder($parameters)->get();
            $recordsData = $records->groupBy('tax_type_id');

            $pdf = PDF::loadView('exports.business.pdf.taxtype',compact('records','recordsData', 'parameters'));
            $pdf->setPaper('a4', 'landscape');
            $pdf->setOption(['dpi' => ReportStatus::DPI_150, 'defaultFont' => 'sans-serif']);
            return $pdf->download('taxtype.pdf');
        } catch (\Exception $exception){
            Log::error($exception);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

    public function exportBusinessesTaxpayerReportPdf($data)
    {
        if (!Gate::allows('managerial-report-pdf')) {
            abort(403);
        }

        try {
            $parameters = json_decode(decrypt($data),true);
            $records = $this->getBusinessBuilder($parameters)->get();
            $recordsData = $records->groupBy('taxpayer_id');
            $pdf = PDF::loadView('exports.business.pdf.taxpayer',compact('records','recordsData', 'parameters'));
            $pdf->setPaper('a4', 'landscape');
            $pdf->setOption(['dpi' => ReportStatus::DPI_150, 'defaultFont' => 'sans-serif']);
            return $pdf->download('taxpayer.pdf');
        } catch (\Exception $exception){
            Log::error($exception);
            session()->flash('error', CustomMessage::error());
            return back();
        }
}
}
