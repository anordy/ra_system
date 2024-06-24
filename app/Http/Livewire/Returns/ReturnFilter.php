<?php

namespace App\Http\Livewire\Returns;

use App\Enum\CustomMessage;
use App\Enum\ReportStatus;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ReturnFilter extends Component
{
    public $payment_type;
    public $penaltyTable;
    public $returnTable;
    public $optionYears;
    public $tableName;
    public $cardOne;
    public $cardTwo;
    public $month;
    public $year;
    public $from;
    public $to;

    public function mount($tablename, $cardOne, $cardTwo)
    {
        //set current year at first
        $this->year = date('Y');
        $this->month = strval(intval(date('m')));
        $this->period = ReportStatus::MONTHLY;
        $this->cardOne = $cardOne;
        $this->cardTwo = $cardTwo;
        $this->tableName = $tablename;
        //get options for years
        $optionStartYear = date('Y');
        $this->optionYears = range($optionStartYear, date('Y'));

        //add All to year options
        $this->optionYears[] = ReportStatus::All;
        //sort array
        rsort($this->optionYears);
        //add Range to year options
        $this->optionYears[] = ReportStatus::CUSTOM_RANGE;
    }

    public function filter()
    {
        try {
            $filters = [
                'type' => $this->payment_type,
                'year' => $this->year,
                'month' => $this->month,
                'from' => $this->from,
                'to' => $this->to,
            ];

            $this->emitTo($this->tableName, 'filterData', $filters);
            $this->emitTo($this->cardOne, 'filterData', $filters);
            $this->emitTo($this->cardTwo, 'filterData', $filters);

        } catch (\Exception $exception) {
            Log::error('RETURNS-RETURN-FILTER-FILTER', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }

    }

    public function render()
    {
        return view('livewire.returns.return-filter');
    }
}
