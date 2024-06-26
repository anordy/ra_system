<?php

namespace App\Http\Livewire\Returns\LumpSum;

use App\Enum\CustomMessage;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Traits\ReturnFilterTrait;

/**
 * Display summary of returns data i.e. total filed, late filed,
 * in-time filed, paid, unpaid and late paid returns
 */
class LumpSumCardOne extends Component
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
            Log::error('RETURNS-LUMPSUM-CARD-ONE', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    public function mount()
    {
        $returnTable = LumpSumReturn::getTableName();
        $filter      = (new LumpSumReturn)->newQuery();
        $filter      = $this->dataFilter($filter, $this->data, $returnTable);
        $this->vars  = $this->getSummaryData($filter);
    }

    public function render()
    {
        return view('livewire.returns.lump-sum.lump-sum-card-one');
    }
}
