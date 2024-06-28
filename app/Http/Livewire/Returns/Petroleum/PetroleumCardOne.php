<?php

namespace App\Http\Livewire\Returns\Petroleum;

use App\Enum\CustomMessage;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Traits\CustomAlert;
use App\Traits\ReturnFilterTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

/**
 * Display summary of returns data i.e. total filed, late filed,
 * in-time filed, paid, unpaid and late paid returns
 */
class PetroleumCardOne extends Component
{
    use ReturnFilterTrait, CustomAlert;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];
    protected $data;
    public $vars; // Carry summary of returns data e.g. total filed

    public function filterData($data)
    {
        try {
            $this->emit('$refresh');
            $this->data = $data;
            self::mount();
        } catch (\Exception $exception) {
            Log::error('RETURNS-PETROLEUM-CARD-TWO', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    public function mount()
    {
        $returnTable = PetroleumReturn::getTableName();
        $filter      = (new PetroleumReturn())->newQuery();
        $filter      = $this->dataFilter($filter, $this->data, $returnTable);
        $this->vars  = $this->getSummaryData($filter);
    }

    public function render()
    {
        return view('livewire.returns.petroleum.petroleum-card-one');
    }
}
