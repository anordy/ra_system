<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

use App\Traits\AssessmentReportTrait;

// use Maatwebsite\Excel\Concerns\FromCollection;

class AssessmentReportExport implements FromView, WithEvents, ShouldAutoSize
{
    use AssessmentReportTrait;

    public $records;
    public $title;
    public $parameters;

    /**
     * __construct
     *
     * @param  mixed $request
     * @return void
     */
    function __construct($records, $title, $parameters)
    {
        $this->records = $records;
        $this->title = $title;
        $this->parameters = $parameters;
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
        $records = $this->records->get();
        $title = $this->title;
        $parameters = $this->parameters;
        return view('exports.assessments.reports.excel.assessment', compact('records', 'title', 'parameters'));
    }
}
