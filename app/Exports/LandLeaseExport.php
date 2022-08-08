<?php

namespace App\Exports;

use App\Models\LandLease;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LandLeaseExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    public $startDate;
    public $endDate;

    //constructor to pass the start and end date
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    // public function collection()
    // {
    //     if ($this->startDate == null && $this->endDate == null) {
    //         return LandLease::all();
    //     } else {
    //         // return LandLease::whereBetween('created_at', [$this->startDate, $this->endDate])->get();
    //         return LandLease::with('taxpayer', 'region', 'district', 'ward')->whereBetween('created_at', [$this->startDate, $this->endDate])->get();
    //     }
    // }

    public function query()
    {
        if ($this->startDate == null && $this->endDate == null) {
            return LandLease::query();
        } else {
            return LandLease::query()->with('taxpayer', 'region', 'district', 'ward')->whereBetween('land_leases.created_at', [$this->startDate, $this->endDate]);
        }
    }

    //map the data to the columns
    public function map($landLease): array
    {
        return [
            $landLease->created_at->format('d/m/Y'),
            $landLease->dp_number,
            $landLease->is_registered==1?$landLease->taxpayer->first_name.' '.$landLease->taxpayer->last_name:$landLease->name,
            //format the date from string
            date('d/m/Y', strtotime($landLease->commence_date)),
            // $landLease->commence_date,
            $landLease->payment_month,
            $landLease->payment_amount,
            $landLease->review_schedule.' years',
            $landLease->valid_period_term.' years',
            $landLease->region->name,
            $landLease->district->name,
            $landLease->ward->name,
            $landLease->is_registered==1?$landLease->taxpayer->mobile:$landLease->phone,
            $landLease->is_registered==1?$landLease->taxpayer->email:$landLease->email,
            $landLease->is_registered==1?$landLease->taxpayer->physical_address:$landLease->address,
            $landLease->is_registered==1?'Registered':'Unregistered',
            $landLease->taxpayer->reference_no,
        ];
    }

    //heading for the columns
    public function headings(): array
    {
        return [
            'Registered Date',
            'DP Number',
            'Name',
            'Commence Date',
            'Payment Month',
            'Payment Amount (USD)',
            'Review Schedule',
            'Valid Period Term',
            'Region',
            'District',
            'Ward',
            'Phone',
            'Email',
            'Address',
            'Applicant Type',
            'ZRB No',   
        ];
    }

}
