<?php

namespace App\Http\Livewire\Mvr;

use App\Models\Bank;
use App\Services\TRA\ServiceRequest;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ChassisNumberSearch extends Component
{

    use LivewireAlert;

    public $chassis_number;
    public $result_route;


    protected function rules()
    {
        return [
            'chassis_number' => 'required|strip_tag',
        ];
    }

    public function mount($result_route)
    {
        $this->result_route = $result_route;
    }


    public function submit()
    {
        $this->validate();
        try{
            return redirect()->to(route($this->result_route,$this->chassis_number));
        }catch(Exception $e){
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.mvr.chassis-search');
    }
}
