<?php

namespace App\Http\Controllers;
use PDF;
use App\Models\WaResponsiblePerson;

class WithholdingAgentController extends Controller
{
    public function index()
    {
        return view('withholding-agent.index');
    }

    public function view()
    {
        return view('withholding-agent.view');
    }

    public function registration()
    {
        return view('withholding-agent.registration');
    }

    public function certificate($id){
        $id = decrypt($id);
        $wa_responsible_person = WaResponsiblePerson::with('taxpayer')->find($id);
        $pdf = PDF::loadView('withholding-agent.certificate', compact('wa_responsible_person'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->stream();
  
    }

}
