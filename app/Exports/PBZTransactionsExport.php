<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PBZTransactionsExport implements  FromView, WithEvents, ShouldAutoSize, WithColumnFormatting
{
    public $records;
    public $title;

    /**
     * __construct
     *
     * @param  mixed $request
     * @return void
     */
    function __construct($records, $title)
    {
        $this->records = $records;
        $this->title = $title;
    }

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

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
//            'I' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function view(): View
    {
        $records = $this->records;
        $title = $this->title;
        return view('exports.payments.reports.excel.pbz-transactions', compact('records', 'title'));
    }
}
