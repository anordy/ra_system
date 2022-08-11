<?php
namespace App\Http\Controllers\Returns\Petroleum;

use PDF;
use App\Http\Controllers\Controller;
use App\Models\Returns\Petroleum\QuantityCertificate;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class QuantityCertificateController extends Controller
{
    use LivewireAlert;

    public function index()
    {
        return view('returns.petroleum.quantity_certificate.index');
    }

    public function create()
    {
        // $checkAnyPending = QuantityCertificate::where('status', 'pending')->first();
        // if($checkAnyPending) {
        //     $this->alert('warning', 'Client must complete filing for previous certificate first.');
        //     return back();
        // }
        return view('returns.petroleum.quantity_certificate.create');
    }

    public function edit($id)
    {
        return view('returns.petroleum.quantity_certificate.edit', compact('id'));
    }

    public function show($id)
    {
        return view('returns.petroleum.quantity_certificate.show', compact('id'));
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
