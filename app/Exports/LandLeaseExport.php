<?php

namespace App\Exports;


use App\Models\LandLease;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

// use Maatwebsite\Excel\Concerns\FromCollection;

class LandLeaseExport implements FromView, WithEvents,ShouldAutoSize
{
    public $startDate;
    public $endDate;

    //constructor to pass the start and end date
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
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
        if ($this->startDate == null && $this->endDate == null) {
            $landLeases =  LandLease::query()->get();
        } else {
            $landLeases = LandLease::query()->with('taxpayer', 'region', 'district', 'ward')->whereBetween('land_leases.created_at', [$this->startDate, $this->endDate])->get();
        }
        $startDate = date('d/m/Y', strtotime($this->startDate));
        $endDate = date('d/m/Y', strtotime($this->endDate));
        // $startDate = $this->startDate;
        // $endDate = $this->endDate;
        return view('exports.land-lease.excel.land-lease-report',compact('landLeases','startDate','endDate'));
    }
}