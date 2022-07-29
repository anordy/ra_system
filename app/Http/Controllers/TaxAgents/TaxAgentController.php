<?php

namespace App\Http\Controllers\TaxAgents;

use App\Http\Controllers\Controller;
use App\Models\TaPaymentConfiguration;
use App\Models\TaxAgent;
use App\Models\TaxAgentAcademicQualification;
use App\Models\TaxAgentProfessionals;
use App\Models\TaxAgentTrainingExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use PDF;

class TaxAgentController extends Controller
{

	public function index(){

		return view('taxagents.index');
	}

	public function activeAgents()
	{
		return view('taxagents.activeTaxagents');
	}

	public function showActiveAgent($id)
	{
		$id = Crypt::decrypt($id);
		$agent = TaxAgent::findOrfail($id);
		return view('taxagents.active-agent-show', compact('agent', 'id'));
	}

	public function showAgentRequest($id)
	{
		$id = Crypt::decrypt($id);
		$agent = TaxAgent::findOrfail($id);

		return view('taxagents.request-agent-show', compact('agent', 'id'));
	}

	public function showVerificationAgentRequest($id)
	{
		$id = Crypt::decrypt($id);
		$agent = TaxAgent::findOrfail($id);
        $fee = DB::table('ta_payment_configurations')
            ->where('category', '=', 'registration fee')->first();
		return view('taxagents.verification-request-agent-show', compact('agent', 'id', 'fee'));
	}

	public function renewal()
	{
		return view('taxagents.renewalRequests');
	}

	public function fee()
	{
		return view('taxagents.fee-config');
	}

    public function certificate($id){
        $id = decrypt($id);
        $taxagent = TaxAgent::with('taxpayer')->find($id);
//        dd($taxagent->taxpayer);
        $pdf = PDF::loadView('taxagents.certificate', compact('taxagent'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->stream();

    }
}
