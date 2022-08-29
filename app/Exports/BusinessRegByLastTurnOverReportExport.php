<?php

namespace App\Exports;

use App\Traits\RegistrationReportTrait;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Traits\ReturnReportTrait;

// use Maatwebsite\Excel\Concerns\FromCollection;

class BusinessRegByLastTurnOverReportExport implements FromView, WithEvents, ShouldAutoSize
{
    use RegistrationReportTrait;

    public $from;
    public $to;

    /**
     * __construct
     *
     * @param  mixed $request
     * @return void
     */
    function __construct($from,$to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * registerEvents
     *
     * @return array
     */
    public function registerEvents(): array
    {

        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'text-align' => 'center'
        ];


        return [

            AfterSheet::class => function (AfterSheet $event) use ($headerStyle) {
                $event->sheet->getDelegate()->getStyle('A1')->applyFromArray($headerStyle);
            }

        ];
    }

    public function view(): View
    {
        $records = $this->businessByTurnOverLastQuery($this->from,$this->to)->get();
        $title = 'Registered Business By Last 12 MonthsTurn Over Report';
        return view('exports.registration.excel.business-by-pre-turn-over', compact('title','records'));
    }
}
