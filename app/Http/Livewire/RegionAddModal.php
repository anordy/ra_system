<?php

namespace App\Http\Livewire;

use App\Models\Region;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RegionAddModal extends Component
{

    use LivewireAlert;

    public $name, $location;


    protected function rules()
    {
        return [
            'name' => 'required|min:2|unique:regions',
            'location' => 'required',
        ];
    }


    public function submit()
    {
        if (!Gate::allows('setting-region-add')) {
            abort(403);
        }

        $this->validate();
        try{
            DB::beginTransaction();
            Region::create([
                'name' => $this->name,
                'location' => $this->location
            ]);

            DB::commit();
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            Log::error($e .', '. Auth::user());
            DB::rollBack();
            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
        }
    }

    public function render()
    {
        return view('livewire.region-add-modal');
    }
}
