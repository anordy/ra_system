<?php

namespace App\Http\Livewire\TaxAgent;

use App\Models\TaxAgent;
use Livewire\Component;

class TaxAgentRequestView extends Component
{
	public $value;
	public $agents = [] ;
	public $academics;
	public $professionals = [] ;
	public $trainings = [] ;

	public function mount($val){
		$this->value = $val;
		$this->agents[] = TaxAgent::find($this->value); // todo: encrypt id
		$this->academics= (object)TaxAgent::find($this->value)->academics;
		$this->professionals = (object)TaxAgent::find($this->value)->professionals;
		$this->trainings = (object)TaxAgent::find($this->value)->trainings;
	}


	public function render()
    {
        return view('livewire.tax-agent.tax-agent-request-view');
    }
}
