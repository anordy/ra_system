<?php

namespace App\Http\Controllers\TaxAgents;

use App\Http\Controllers\Controller;
use App\Models\TaxAgent;
use App\Models\TaxAgentAcademicQualification;
use App\Models\TaxAgentProfessionals;
use App\Models\TaxAgentTrainingExperience;
use Illuminate\Http\Request;

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
		$agent = TaxAgent::findOrfail($id);
		$t_id = $agent->id;
		$education = TaxAgentAcademicQualification::query()->where('tax_agent_id', $t_id)->get();
		$prof = TaxAgentProfessionals::query()->where('tax_agent_id', $t_id)->get();
		$tra = TaxAgentTrainingExperience::query()->where('tax_agent_id', $t_id)->get();
		return view('taxagents.active-agent-show', compact('agent', 'education', 'prof', 'tra'));
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
