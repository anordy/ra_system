<?php

namespace App\Http\Livewire\Settings\TaxRegion;

use App\Models\TaxRegion;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class TaxRegionAddModal extends Component
{
    use LivewireAlert;

    public $prefix;
    public $name;

    protected function rules()
    {
        return [
            'prefix' => 'required|unique:tax_regions,prefix|digits:2',
            'name' => 'required|unique:tax_regions,name',
        ];
    }

    public function submit()
    {
        $this->validate();
        try {
            TaxRegion::create([
                'prefix' => $this->prefix,
                'name' => $this->name,
                'code' => str_replace(' ', '-', strtolower($this->name))
            ]);
            $this->flash('success', 'Tax region saved.', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
        }
    }

    public function render()
    {
        return view('livewire.settings.tax-region.add-modal');
    }
}
