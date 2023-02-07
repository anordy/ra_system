<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;


class DailyPaymentExport implements FromView, WithEvents, ShouldAutoSize
{

    public $vars;
    public $title;
    public $taxTypes;

    /**
     * __construct
     *
     * @param $vars
     * @param $taxTypes
     * @param $title
     */
    function __construct($vars,$taxTypes, $title)
    {
        $this->vars = $vars;
        $this->title = $title;
        $this->taxTypes = $taxTypes;
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
        $vars = $this->vars;
        $title = $this->title;
        $taxTypes = $this->taxTypes;
        return view('exports.payments.reports.excel.daily-payment', compact('vars','taxTypes','title'));
    }
}
