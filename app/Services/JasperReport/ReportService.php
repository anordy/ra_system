<?php
/**
 * Created by mailto:baraka.machumu@ubx.co.tz
 * 14/11/2023
 */

namespace App\Services\JasperReport;

use App\Helpers\ReportFormat;
use Illuminate\Support\Facades\Response;

class ReportService
{

    public static function printReport($report, $format, $reportname)
    {
        // $filename  = date('Y-m-d his').time().'.'.$format;
        $filename = $reportname . '_' . date('Y-m-d_is') . '.' . $format;
        $file_path = public_path() . '/' . $filename;
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Description: File Transfer');
        // header('Content-Disposition: attachment; filename=report.' . $format);
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($report));
        header('Content-Type: application/' . $format);
        echo $report;
        exit();
    }

    public function getReport($report, $format = ReportFormat::PDF, $inputControls = [])
    {
        $hasParam = $report->has_parameter;
        $reportPath = $report->report_url;

        return $this->generateTemplate($format, $reportPath, $inputControls, $hasParam);
    }

    public function generateTemplate($format, $reportPath, $inputControls, $hasParam = 0)
    {

        $connection = JasperConnection::getConnectionInstance();
        if ($hasParam) {
            $report = $connection->reportService()
                ->runReport($reportPath, $format, null, null, $inputControls);
        } else {
            //  $job = new Job();
            //   $job->setReport("/path/to/your/report");
            //  $report = $connection->jobService()->createJob();

            $report = $connection->reportService()->runReport($reportPath, $format);
        }

        $reportExecutionId = uniqid('report_', true);

        if ($format == ReportFormat::PDF) {
            $filePath = storage_path('app/reports') . $reportExecutionId .'.pdf';
            file_put_contents($filePath, $report);
            return $filePath;
           return self::printPdf($report);
        } elseif ($format == ReportFormat::EXCEL || ReportFormat::CSV) {
            return self::printExcelAndCsv($report, $format);
        } else {
            return response()->json(['error' => true, 'message' => 'Invalid report format']);
        }
        return response()->json(['error' => true, 'message' => 'Process completed']);
    }


    public static function printPdf($report)
    {
        ReportFormat::pdf($report);
        echo $report;
        exit();
    }

    public static function printExcelAndCsv($report, $format)
    {
        $filename = date('Y-m-d his') . time() . '.' . $format;
        $file_path = storage_path() . '/reports/' . $filename;
        file_put_contents($file_path, $report);

        return Response::download($file_path, $filename, [
            'Content-Type: application/' . $format
        ]);
    }


}
