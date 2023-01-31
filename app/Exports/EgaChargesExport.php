<?php

namespace App\Exports;

use App\Traits\PaymentReportTrait;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EgaChargesExport implements FromView, WithEvents, ShouldAutoSize
{
    use PaymentReportTrait;

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
        $title = $this->title;
        $parameters = $this->parameters;
        $currency = $this->parameters['currency'];
        $range_start = $this->parameters['range_start'];
        $range_end = $this->parameters['range_end'];
        $payment_status = $this->parameters['payment_status'];
        $charges_type = $this->parameters['charges_type'];
        $records = $this->getEgaChargesQuery($range_start,$range_end,$currency,$payment_status,$charges_type)->get();
        return view('exports.payments.reports.excel.ega-charges', compact('records', 'title', 'parameters'));
    }
}
