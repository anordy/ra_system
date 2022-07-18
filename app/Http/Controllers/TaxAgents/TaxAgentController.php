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

class TaxAgentController extends Controller
{

	public function index(){
		$fee = DB::table('ta_payment_configurations')
		  ->where('category', '=', 'registration fee')->first();
		return view('taxagents.index',compact('fee'));
	}

	public function activeAgents()
	{
		return view('taxagents.activeTaxagents');
	}

	public function showActiveAgent($id)
	{
		$id = Crypt::decrypt($id);
		$agent = TaxAgent::findOrfail($id);
		return view('taxagents.active-agent-show', compact('agent'));
	}

	public function showAgentRequest($id)
	{
		$id = Crypt::decrypt($id);
		$agent = TaxAgent::findOrfail($id);
		return view('taxagents.request-agent-show', compact('agent'));
	}

	public function showVerificationAgentRequest($id)
	{
		$id = Crypt::decrypt($id);
		$agent = TaxAgent::findOrfail($id);
		return view('taxagents.verification-request-agent-show', compact('agent'));
	}

	public function renewal()
	{
		return view('taxagents.renewalRequests');
	}

	public function fee()
	{
		return view('taxagents.fee-config');
	}
}
