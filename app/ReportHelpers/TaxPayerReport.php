<?php

namespace App\ReportHelpers;

use Illuminate\Support\Facades\DB;

class TaxPayerReport
{

    public  static  function  getTaxPayer($selector,$data){
        if ($selector){
            $res  =  DB::select("select bl.name as business_location,bl.created_at,b.name as business_name,bl.zin,tr.name as tax_region_name,bl.tax_region_id
                        from business_locations bl
                        inner join businesses b on b.id  = bl.business_id
                        inner join tax_regions tr  on tr.id  =  bl.tax_region_id 
                        where b.status  = 'approved'");
        }
        else{
            $data =     DB::select("select bl.name as business_location,bl.created_at,b.name as business_name,bl.zin,tr.name as tax_region_name,bl.tax_region_id
                        from business_locations bl
                        inner join businesses b on b.id  = bl.business_id
                        inner join tax_regions tr  on tr.id  =  bl.tax_region_id 
                        where b.status  = 'approved'
                    AND bl.created_at BETWEEN TO_DATE(:start_date, 'YYYY-MM-DD') AND TO_DATE(:end_date, 'YYYY-MM-DD')
                ", [
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date']
            ]);
        }

        return $data;
    }

    public static function getTaxPayerContribution(bool $selector, $data)
    {
        if ($selector){
            $res  =  DB::select("
                                    select tx.total_amount,tx.created_at, bl.name as business_location,bl.created_at,b.name as business_name,bl.zin,tr.name as tax_region_name,bl.tax_region_id
                                    
                                    from tax_returns  tx
                                    inner join businesses b on b.id  = tx.business_id
                                    inner join business_locations bl on bl.business_id=  b.id
                                    inner join tax_regions tr  on tr.id  =  bl.tax_region_id 
                                    
                                    where  tx.payment_status  = 'complete';");
        }else{
            $data =     DB::select("
                                    select tx.total_amount,tx.created_at, bl.name as business_location,bl.created_at,b.name as business_name,bl.zin,tr.name as tax_region_name,bl.tax_region_id
                                    
                                    from tax_returns  tx
                                    inner join businesses b on b.id  = tx.business_id
                                    inner join business_locations bl on bl.business_id=  b.id
                                    inner join tax_regions tr  on tr.id  =  bl.tax_region_id 
                                    
                                    where  tx.payment_status  = 'complete';
                                    AND bl.created_at BETWEEN TO_DATE(:start_date, 'YYYY-MM-DD') AND TO_DATE(:end_date, 'YYYY-MM-DD')
                                ", [
                                'start_date' => $data['start_date'],
                                'end_date' => $data['end_date']
                            ]);
        }

        return $data;
    }


    public  static  function  getTaxPayerForPastTwelveMonth(){

        return DB::select("SELECT t.total_amount, b.name AS business_name, b.ztn_number, t.created_at
                                FROM tax_returns t
                                INNER JOIN businesses b ON b.id = t.business_id
                                WHERE t.payment_status = 'complete' AND t.created_at >= ADD_MONTHS(SYSDATE, -12);");
    }


    public static function getHotelData(bool $selector, $data)
    {
        if ($selector){
            $res  =  DB::select("select bl.name  as business_name ,h.management_company,h.created_at,h.number_of_rooms,h.hotel_location,
                                        h.number_of_rooms as number_of_beds,h.average_rate,hs.name as stars
                                        from  business_hotels h 
                                        inner join hotel_stars hs on hs.id  =  h.hotel_star_id
                                        inner join business_locations bl on bl.business_id=  h.business_id");
        }else{
            $data =     DB::select("select bl.name  as business_name ,h.management_company,h.created_at,h.number_of_rooms,h.hotel_location,
                                    h.number_of_rooms as number_of_beds,h.average_rate,hs.name as stars
                                    from  business_hotels h 
                                    inner join hotel_stars hs on hs.id  =  h.hotel_star_id
                                    inner join business_locations bl on bl.business_id=  h.business_id
                                                                        where   h.created_at BETWEEN TO_DATE(:start_date, 'YYYY-MM-DD') AND TO_DATE(:end_date, 'YYYY-MM-DD')
                                                                    ", [
                                                    'start_date' => $data['start_date'],
                                                    'end_date' => $data['end_date']
            ]);
        }

        return $data;
    }

    public static function getRentingPremissesData(bool $selector, array $data)
    {
        if ($selector){
            $res  =  DB::select("select tp.mobile, bl.name as location_name,b.name as business_name,tp.first_name,tp.middle_name,tp.last_name,bl.created_at,bl.zin
                                        from business_locations bl
                                        inner join businesses b on b.id  = bl.business_id
                                        inner join taxpayers tp on tp.id  = bl.taxpayer_id
");
        }else{
            $data =     DB::select("select tp.mobile, bl.name as location_name,b.name as business_name,tp.first_name,tp.middle_name,tp.last_name,bl.created_at,bl.zin
                                        from business_locations bl
                                        inner join businesses b on b.id  = bl.business_id
                                        inner join taxpayers tp on tp.id  = bl.taxpayer_id  where   bl.created_at BETWEEN TO_DATE(:start_date, 'YYYY-MM-DD') AND TO_DATE(:end_date, 'YYYY-MM-DD')", [
                                            'start_date' => $data['start_date'],
                                      'end_date' => $data['end_date']
            ]);
        }

        return $data;
    }

    public static function getFiledTaxPayerData(bool $selector, array $data)
    {
        if ($selector){
            $res  =  DB::select("select tx.total_amount,tx.created_at, bl.name as business_location,bl.created_at,b.name as business_name,bl.zin,tr.name as tax_region_name,tr.location as department
from tax_returns  tx
inner join businesses b on b.id  = tx.business_id
inner join business_locations bl on bl.business_id=  b.id
inner join tax_regions tr  on tr.id  =  bl.tax_region_id 
where  tx.payment_status  = 'complete';");
        }else{
            $data =     DB::select("select tx.total_amount,tx.created_at, bl.name as business_location,bl.created_at,b.name as business_name,bl.zin,tr.name as tax_region_name,tr.location as department
from tax_returns  tx
inner join businesses b on b.id  = tx.business_id
inner join business_locations bl on bl.business_id=  b.id
inner join tax_regions tr  on tr.id  =  bl.tax_region_id 
where  tx.payment_status  = 'complete' and    tx.created_at BETWEEN TO_DATE(:start_date, 'YYYY-MM-DD') AND TO_DATE(:end_date, 'YYYY-MM-DD')", [
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date']
            ]);
        }

        return $data;

    }
}