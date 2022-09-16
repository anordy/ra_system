<?php

namespace App\Exports;

use App\Models\LeasePayment;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LeasePaymentExport implements FromView, WithEvents,ShouldAutoSize
{
    public $startDate;
    public $endDate;
    public $status;
    public $date_type;

    //constructor to pass the start and end date
    public function __construct($startDate, $endDate, $status, $date_type)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->date_type = $date_type;
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
            $leasePayments =  LeasePayment::query();
        } else {
            $model = LeasePayment::query()->with('taxpayer', 'landLease.region', 'landLease.district', 'landLease.ward');
            if ($this->date_type == 'payment_month') {
                $months = $this->getMonthList($this->startDate, $this->endDate);
                $model = clone $model
                ->leftJoin('land_leases', 'land_leases.id', 'lease_payments.land_lease_id')
                ->whereIn("land_leases.{$this->date_type}", $months);
                
            } elseif ($this->date_type == 'payment_year') {
                $years = $this->getYearList($this->startDate, $this->endDate);
                $model = clone $model
                ->leftJoin('financial_years', 'financial_years.id', 'lease_payments.financial_year_id')
                ->whereIn("financial_years.code", $years);

            }else {
                // dd($this->date_type);
                $leasePayments = LeasePayment::query()->with('taxpayer', 'landLease.region', 'landLease.district', 'landLease.ward')->whereBetween("lease_payments.{$this->date_type}", [$this->startDate, $this->endDate]);
            }

            // $leasePayments = LeasePayment::query()->with('taxpayer', 'landLease.region', 'landLease.district', 'landLease.ward')->whereBetween('lease_payments.created_at', [$this->startDate, $this->endDate]);
        }

        if ($this->status) {
            $leasePayments = clone $leasePayments->where('status', $this->status);
        }

        $leasePayments = clone $leasePayments->get();

        // dd($leasePayments);

        $startDate = date('d/m/Y', strtotime($this->startDate));
        $endDate = date('d/m/Y', strtotime($this->endDate));

        return view('exports.land-lease.excel.lease-payment-report',compact('leasePayments','startDate','endDate'));
    }

    public function getMonthList($startDate, $endDate){
        $period = CarbonPeriod::create($startDate, $endDate)->month();

        $months = collect($period)->map(function (Carbon $date) {
        return  $date->monthName;
        })->toArray();

        return $months;
    }

    public function getYearList($startDate, $endDate){
        $period = CarbonPeriod::create($startDate, $endDate)->year();

        $years = collect($period)->map(function (Carbon $date) {
        return  $date->year;
        })->toArray();

        return $years;
    }
}
