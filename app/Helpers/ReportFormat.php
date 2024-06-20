<?php

namespace App\Helpers;

class ReportFormat
{

    const  PDF = 'pdf';
    const  EXCEL = 'xlsx';
    const  CSV = 'csv';

    public  static function pdf($report){
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=report.pdf');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($report));
        header('Content-Type: application/pdf');
    }

    public  static  function  execl(){

    }

    public  static  function  csv(){

    }
}
