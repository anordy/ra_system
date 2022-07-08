<?php

namespace App\Http\Controllers\TaxAgents;

use App\Http\Controllers\Controller;
use App\Models\TaPaymentConfiguration;
use App\Models\TaxAgent;
use App\Models\TaxAgentAcademicQualification;
use App\Models\TaxAgentProfessionals;
use App\Models\TaxAgentTrainingExperience;
use Illuminate\Http\Request;

class TaxAgentController extends Controller
{

	public function index(){
		$fee = TaPaymentConfiguration::all();
		return view('taxagents.index',compact('fee'));
	}

	public function activeAgents()
	{
		return view('taxagents.activeTaxagents');
	}

	public function showActiveAgent($id)
	{
		$agent = TaxAgent::findOrfail($id);
		return view('taxagents.active-agent-show', compact('agent'));
	}

	public function showAgentRequest($id)
	{
		$agent = TaxAgent::findOrfail($id);
		return view('taxagents.request-agent-show', compact('agent'));
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
