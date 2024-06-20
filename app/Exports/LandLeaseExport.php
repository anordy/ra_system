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
    public $taxpayer_id;

    //constructor to pass the start and end date
    public function __construct($startDate, $endDate, $taxpayer_id)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->taxpayer_id = $taxpayer_id;
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
            $landLeases =  LandLease::whereNotNull('land_leases.completed_at')->get();
        } else {
            $landLeases = LandLease::query()->with('taxpayer', 'region', 'district', 'ward')
                ->whereBetween('land_leases.created_at', [$this->startDate, $this->endDate])
                ->whereNotNull('land_leases.completed_at')
                ->get();
        }

        if ($this->taxpayer_id) {
            $landLeases = clone $landLeases->where('taxpayer_id', $this->taxpayer_id);
        }

        $startDate = date('d/m/Y', strtotime($this->startDate));
        $endDate = date('d/m/Y', strtotime($this->endDate));
        return view('exports.land-lease.excel.land-lease-report',compact('landLeases','startDate','endDate'));
    }
}