<?php
/**
 * Created by mailto:baraka.machumu@ubx.co.tz
 * 14/11/2023
 */

namespace App\Services\JasperReport;

use App\Helpers\ReportFormat;

class ReportService
{

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
        } elseif ($format == ReportFormat::EXCEL || ReportFormat::CSV) {
            $filePath = storage_path('app/reports') . $reportExecutionId .'.csv';
            file_put_contents($filePath, $report);
            return $filePath;
        } else {
            return response()->json(['error' => true, 'message' => 'Invalid report format']);
        }
    }


}
