<?php
namespace App\Http\Controllers\Returns\Petroleum;

use PDF;
use App\Http\Controllers\Controller;
use App\Models\Returns\Petroleum\QuantityCertificate;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class QuantityCertificateController extends Controller
{
    use LivewireAlert;

    public function index()
    {
        if (!Gate::allows('certificate-of-quantity-view')) {
            abort(403);
        }

        return view('returns.petroleum.quantity_certificate.index');
    }

    public function create()
    {
        if (!Gate::allows('certificate-of-quantity-create')) {
            abort(403);
        }

        return view('returns.petroleum.quantity_certificate.create');
    }

    public function edit($id)
    {
        $certificate =QuantityCertificate::with('business')->findOrFail(decrypt($id));

        if(($certificate->status ?? '') == 'filled'){
            abort(403);
        }

        return view('returns.petroleum.quantity_certificate.edit', compact('id'));
    }

    public function show($id)
    {
        if (!Gate::allows('certificate-of-quantity-view')) {
            abort(403);
        }

        return view('returns.petroleum.quantity_certificate.show', compact('id'));
    }


    public function certificate($id)
    {
        $data = QuantityCertificate::with('business')->findOrFail(decrypt($id));

        $certificate = QuantityCertificate::with('business')->findOrFail(decrypt($id));

        if(($certificate->status ?? '') == 'filled'){
            abort(403);
        }

        $view = view('returns.petroleum.quantity_certificate.pdf', compact('data'));
        $html = $view->render();
        $pdf = PDF::loadHTML($html);
        return $pdf->stream('id_' . time() . '.pdf');
    }

    
}
