<?php

namespace App\Http\Livewire\Reports\TaxPayer;

use App\Models\Reports\Report;
use App\Models\Reports\ReportType;
use App\ReportHelpers\TaxPayerReport;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TaxPayer extends Component
{
    public  $report_types = [];
    public  $report_type_id;
    public  $reports = [];
    public  $report_code, $start_date,$format,$end_date;

    public function mount(){
        $this->report_types  = ReportType::query()
            ->select('id','name')->get();

        if ($this->report_type_id){
            $this->reports=   Report::query()
                ->select('code','name','has_parameter')
                ->where('report_type_id',$this->report_type_id)
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.reports.tax-payer.tax-payer');
    }

    public  function  updated($property){
        if ($property=='report_type_id'){
            $this->reports=   Report::query()
                ->select('code','name','has_parameter', 'id')
                ->where('report_type_id',$this->report_type_id)
                ->get();
        }

    }

    public function submit(){
        $report_type_id =  $this->report_type_id;
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        $report_code = $this->report_code;

        try {

            if ($report_code=='100'){
                $results=   self::getGfsData($start_date,$end_date);
            }
            elseif ($report_code=='101'){
                $results =  $this->getTaxPayerData($start_date,$end_date);
            }

            elseif ($report_code=='102'){
                $results =  $this->getTaxPayerContributionData($start_date,$end_date);
            }

            elseif ($report_code=='103'){
                $results =  TaxPayerReport::getTaxPayerForPastTwelveMonth();
            }

            elseif ($report_code=='104'){
                $results =  $this->getHotelDataReport($start_date,$end_date);
            }

            elseif ($report_code=='105'){
                $results =  $this->getRentingPremisses($start_date,$end_date);
            }

            elseif ($report_code=='106'){
                $results =  $this->getFiledTaxPayer($start_date,$end_date);
            }

            elseif ($report_code=='107'){
                $results =  $this->getNonFiledTaxPayer($start_date,$end_date);
            }

            elseif ($report_code=='108'){
                $results =  $this->getFiledTaxPayer($start_date,$end_date);
            }


            dd($results);


        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public  static  function  getRentingPremisses($start_date,$end_date){
        if (is_null($start_date)) {
            // Fetch data without date range
            $results = TaxPayerReport::getRentingPremissesData(false,null);
        } else {
            // Fetch data with date range
            $results = TaxPayerReport::getRentingPremissesData(false,['start_date'=>$start_date,'end_date'=>$end_date]);
        }
        return $results;
    }

    public  static  function  getTaxPayerContributionData($start_date,$end_date){
        if (is_null($start_date)) {
            // Fetch data without date range
            $results = TaxPayerReport::getTaxPayerContribution(false,null);
        } else {
            // Fetch data with date range
            $results = TaxPayerReport::getTaxPayerContribution(false,['start_date'=>$start_date,'end_date'=>$end_date]);
        }
        return $results;
    }

    public  static  function  getTaxPayerData($start_date,$end_date){
        if (is_null($start_date)) {
            // Fetch data without date range
            $results = TaxPayerReport::getTaxPayer(false,null);
        } else {
            // Fetch data with date range
            $results = TaxPayerReport::getTaxPayer(false,['start_date'=>$start_date,'end_date'=>$end_date]);
        }
        return $results;
    }

    public  static  function  getHotelDataReport($start_date,$end_date){
        if (is_null($start_date)) {
            // Fetch data without date range
            $results = TaxPayerReport::getHotelData(false,null);
        } else {
            // Fetch data with date range
            $results = TaxPayerReport::getHotelData(false,['start_date'=>$start_date,'end_date'=>$end_date]);
        }
        return $results;
    }

    public  static  function  getGfsData($start_date,$end_date){
        if (is_null($start_date)) {
            // Fetch data without date range
            $results = self::gfsCodeRevenue(false,null);
        } else {
            // Fetch data with date range
            $results = self::gfsCodeRevenue(false,['start_date'=>$start_date,'end_date'=>$end_date]);
        }
        return $results;
    }

    public  static  function gfsCodeRevenue($selector,$data){

        if ($selector){
            $data=    DB::select("
                    SELECT t.ID, t.created_at, t.PAYMENT_STATUS, t.TOTAL_AMOUNT, su.GFS_CODE, su.NAME AS SUB_VAT_NAME
                    FROM TAX_RETURNS t
                    INNER JOIN SUB_VATS su ON su.ID = t.SUB_VAT_ID
                    WHERE t.PAYMENT_STATUS = 'complete'
                ");
        }
        else{
            $data =     DB::select("
                    SELECT t.ID, t.created_at, t.PAYMENT_STATUS, t.TOTAL_AMOUNT, su.GFS_CODE, su.NAME AS SUB_VAT_NAME
                    FROM TAX_RETURNS t
                    INNER JOIN SUB_VATS su ON su.ID = t.SUB_VAT_ID
                    WHERE t.PAYMENT_STATUS = 'complete'
                    AND t.created_at BETWEEN TO_DATE(:start_date, 'YYYY-MM-DD') AND TO_DATE(:end_date, 'YYYY-MM-DD')
                ", [
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date']
            ]);
        }

        return $data;
    }

    private function getFiledTaxPayer($start_date, $end_date)
    {
        if (is_null($start_date)) {
            // Fetch data without date range
            $results = TaxPayerReport::getFiledTaxPayerData(false,null);
        } else {
            // Fetch data with date range
            $results = TaxPayerReport::getFiledTaxPayerData(false,['start_date'=>$start_date,'end_date'=>$end_date]);
        }
        return $results;
    }

}
