<?php

namespace App\Http\Livewire\Chart;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Chart extends Component
{

    public $pieChartData = [];
    public $barChartData = [];

    public function mount()
    {
        // Fetch and process pie chart data
        $leakages = DB::table('ra_incedents as ra')
            ->select(
                'b.name as channel',
                'iss.type',
                'iss.currency',
                DB::raw('SUM(iss.detected) as detected'),
                DB::raw('SUM(iss.prevented) as prevented'),
                DB::raw('SUM(iss.recovered) as recovered')
            )
            ->leftJoin('bank_channels as b', 'b.id', '=', 'ra.bank_channel_id')
            ->leftJoin('ra_issues as iss', 'iss.ra_incident_id', '=', 'ra.id')
            ->groupBy('b.name', 'iss.type', 'iss.currency')
            ->where('iss.type', '=', 'Revenue Loss')
            ->get();

        $pieData = [];
        foreach ($leakages as $row) {
            $channel = $row->channel;
            $label = "{$row->type} ({$row->currency})";

            if (!isset($pieData[$channel])) {
                $pieData[$channel] = [["Category", "Amount"]];
            }

            $pieData[$channel][] = ["$label - Detected", (int) $row->detected];
            $pieData[$channel][] = ["$label - Prevented", (int) $row->prevented];
            $pieData[$channel][] = ["$label - Recovered", (int) $row->recovered];
        }

        $this->pieChartData = json_encode($pieData);

        // Fetch and process bar chart data
        $channelLeakages = DB::table('ra_incedents as ra')
            ->select(
                'b.name as channel',
                DB::raw('SUM(iss.detected) as detected'),
                DB::raw('SUM(iss.prevented) as prevented'),
                DB::raw('SUM(iss.recovered) as recovered')
            )
            ->leftJoin('bank_channels as b', 'b.id', '=', 'ra.bank_channel_id')
            ->leftJoin('ra_issues as iss', 'iss.ra_incident_id', '=', 'ra.id')
            ->groupBy('b.name')
            ->get();

        $barData = [["Channel", "Detected", "Prevented", "Recovered"]];
        foreach ($channelLeakages as $row) {
            $barData[] = [
                $row->channel,
                (int) $row->detected,
                (int) $row->prevented,
                (int) $row->recovered
            ];
        }

        $this->barChartData = json_encode($barData);
    }

    public function render()
    {
        return view('livewire.chart.chart', [
            'pieChartData' => $this->pieChartData,
            'barChartData' => $this->barChartData
        ]);
    }
}
