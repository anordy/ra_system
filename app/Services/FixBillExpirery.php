<?php

namespace App\Services;

use App\Models\Audit;
use Carbon\Carbon;

class FixBillExpirery
{

    public function run()
    {

        $handle = fopen('audits.csv', 'w+');

        $header = ['S/N', 'Log Type', 'User Name', 'IP Address', 'Info', 'User Agent', 'Event Time']; // Replace with your actual column names
        fputcsv($handle, $header, ';');

        Audit::query()->select(['id', 'user_type', 'user_id', 'event', 'auditable_id', 'auditable_type', 'ip_address', 'user_agent', 'created_at'])
            ->whereBetween('created_at', [Carbon::create('2024-04-01'), Carbon::create('2024-06-30')])
            ->chunk(500, function ($users) use ($handle) {
                foreach ($users as $i => $row) {
                    $rowArray = $row->toArray();

                    $model = preg_split('[\\\]', $row->auditable_type)[2];
                    $label = preg_replace('/(?<=[a-z])[A-Z]|[A-Z](?=[a-z])/', ' $0', $model);
                    $event = ucfirst($row->event);
                    $fullName = ucfirst($row->user->fullname ?? '-');

                    if ($row->event === 'logged in') {
                        $logType = "{$fullName} {$event}";
                    } else if ($row->event === 'logged out') {
                        $logType = "{$fullName} {$event}";
                    } else {
                        $logType = "{$event} {$label} with id {$row->auditable_id}";
                    }

                    $mappedData = [
                        $i+1,
                        $logType,
                        $fullName,
                        $row->ip_address,
                        '',
                        $row->user_agent,
                        $row->created_at
                    ];


                    fputcsv($handle, $mappedData, ';');
                }
            });

        fclose($handle);

    }

}