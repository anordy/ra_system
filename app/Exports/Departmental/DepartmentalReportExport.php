<?php

namespace App\Exports\Departmental;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class DepartmentalReportExport implements FromView, WithEvents, ShouldAutoSize
{

    public $report;
    public $title;
    public $nonRevenueTaxTypes;
    public $domesticTaxTypes;
    public $vars;

    /**
     * __construct
     *
     * @param $report
     * @param $taxTypes
     * @param $title
     */
    function __construct($report, $title, $nonRevenueTaxTypes, $domesticTaxTypes, $vars)
    {
        $this->report = $report;
        $this->title = $title;
        $this->domesticTaxTypes = $domesticTaxTypes;
        $this->nonRevenueTaxTypes = $nonRevenueTaxTypes;
        $this->vars = $vars;
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
        return view('exports.reports.departmental.departmental', [
            'report' => $this->report,
            'title' => $this->report,
            'nonRevenueTaxTypes' => $this->nonRevenueTaxTypes,
            'domesticTaxTypes' => $this->domesticTaxTypes,
            'vars' => $this->vars,
        ]);
    }
}
