<?php

namespace App\Http\Livewire\Chart;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PieChart extends Component
{

    public $chartData = [];

    public function mount()
    {
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
        ->get();

    $data = [];

    foreach ($leakages as $row) {
        $channel = $row->channel;
        $label = "{$row->type} ({$row->currency})"; // Example: "Fraud (USD)"

        if (!isset($data[$channel])) {
            $data[$channel] = [["Category", "Amount"]]; // Header for Google Charts
        }

        $data[$channel][] = ["$label - Detected", (int) $row->detected];
        $data[$channel][] = ["$label - Prevented", (int) $row->prevented];
        $data[$channel][] = ["$label - Recovered", (int) $row->recovered];
    }

    $this->chartData = json_encode($data);
    }

    public function render()
    {
        return view('livewire.chart.pie-chart');
    }
}
