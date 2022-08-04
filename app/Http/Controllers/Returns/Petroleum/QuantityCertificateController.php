<?php
namespace App\Http\Controllers\Returns\Petroleum;
use PDF;

use App\Http\Controllers\Controller;
use App\Models\QuantityCertificate;

class QuantityCertificateController extends Controller
{
    public function index()
    {
        return view('returns.petroleum.quantity_certificate.index');
    }

    public function create()
    {
        return view('returns.petroleum.quantity_certificate.create');
    }

    public function certificate($id)
    {
        $data = QuantityCertificate::with('business')->find(decrypt($id));
        $view = view('returns.petroleum.quantity_certificate.pdf', compact('data'));
        $html = $view->render();
        $pdf = PDF::loadHTML($html);
        return $pdf->stream('id_' . time() . '.pdf');
    }

    
}
