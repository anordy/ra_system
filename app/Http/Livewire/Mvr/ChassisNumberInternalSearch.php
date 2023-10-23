<?php

namespace App\Http\Livewire\Mvr;

use App\Models\Bank;
use App\Services\TRA\ServiceRequest;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class ChassisNumberInternalSearch extends Component
{

    use CustomAlert;

    public $number;
    public $type;
    public $result_route;

    protected function rules()
    {
        return [
            'number' => 'required|strip_tag',
            'type' => 'required|strip_tag',
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
            return redirect()->to(route($this->result_route,['type'=>$this->type,'number'=>$this->number]));
        }catch(Exception $e){
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.mvr.chassis-internal-search');
    }
}
