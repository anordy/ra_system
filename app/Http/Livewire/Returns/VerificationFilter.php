<?php

namespace App\Http\Livewire\Returns;

use App\Enum\CustomMessage;
use App\Enum\ReportStatus;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class VerificationFilter extends Component
{
    use CustomAlert;

    public $penaltyTable;
    public $returnTable;
    public $optionYears;
    public $tableName;
    public $month;
    public $year;
    public $from;
    public $to;
    public $period;

    public function mount($tablename)
    {
        //set current year at first
        $this->year      = date('Y');
        $this->month     = strval(intval(date('m')));
        $this->period    = ReportStatus::MONTHLY;
        $this->tableName = $tablename;
        //get options for years
        $optionStartYear   = date('Y');
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
                'year' => $this->year,
                'month'=> $this->month,
                'from' => $this->from,
                'to'   => $this->to,
            ];

            $this->emitTo($this->tableName, 'filterData', $filters);
        } catch (\Exception $exception) {
            Log::error('RETURNS-VERIFICATION-FILTER-FILTER', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }

    }

    public function render()
    {
        return view('livewire.returns.verification-filter');
    }
}
