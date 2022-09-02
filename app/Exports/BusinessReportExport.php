<?php

namespace App\Exports;

use App\Models\TaxType;
use App\Traits\RegistrationReportTrait;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Traits\ReturnReportTrait;

// use Maatwebsite\Excel\Concerns\FromCollection;

class BusinessReportExport implements FromView, WithEvents, ShouldAutoSize
{
    use RegistrationReportTrait;

    public $businesses;

    /**
     * __construct
     *
     * @param  mixed $request
     * @return void
     */
    function __construct($businesses)
    {
        $this->businesses = $businesses;
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
        $records = $this->businesses->get();
        return view('exports.business.excel.business', compact('records'));
    }
}
