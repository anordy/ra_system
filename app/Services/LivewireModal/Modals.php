<?php

namespace App\Services\LivewireModal;

use Livewire\Component;

class Modals extends Component
{
    public $alias;
    public $params = [];

    protected $listeners = ['showModal', 'resetModal'];

    public function render()
    {
        return view('livewire.services.modals');
    }

    public function showModal($alias, ...$params)
    {
        $this->alias = $alias;
        $this->params = $params;
        $this->emit('showBootstrapModal');
    }

    public function resetModal()
    {
        $this->reset();
    }
}