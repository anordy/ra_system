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
    public $location;

    protected function rules()
    {
        return [
            'prefix' => 'required|unique:tax_regions,prefix|digits:2|strip_tag',
            'name' => 'required|unique:tax_regions,name|strip_tag',
            'location' => 'required|strip_tag',
        ];
    }

    public function submit()
    {
        $this->validate();
        try {
            TaxRegion::create([
                'prefix' => $this->prefix,
                'name' => $this->name,
                'code' => str_replace(' ', '-', strtolower($this->name)),
                'location' => $this->location
            ]);
            $this->flash('success', 'Tax region saved.', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.settings.tax-region.add-modal');
    }
}
