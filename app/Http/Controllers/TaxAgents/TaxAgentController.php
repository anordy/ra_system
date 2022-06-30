<?php

namespace App\Http\Controllers\TaxAgents;

use App\Http\Controllers\Controller;
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

	public function renewal()
	{
		return view('taxagents.renewalRequests');
	}

	public function fee()
	{
		return view('taxagents.fee-config');
	}
}
