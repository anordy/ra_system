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

class BusinessRegByTaxTypeReportExport implements FromView, WithEvents, ShouldAutoSize
{
    use RegistrationReportTrait;

    public $taxType;

    /**
     * __construct
     *
     * @param  mixed $request
     * @return void
     */
    function __construct($tax_type_id)
    {
        $this->taxType = TaxType::findOrFail($tax_type_id);
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
        $records = $this->businessByNatureQuery($this->taxType->id)->get();
        $taxType = $this->taxType;
        $title = 'Registered Business By TaxType Report';
        return view('exports.registration.excel.business-by-tax-type', compact('records', 'title','taxType'));
    }
}
