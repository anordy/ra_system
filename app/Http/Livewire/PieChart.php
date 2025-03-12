<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PieChart extends Component
{
    public $labels = [];
    public $data = [];

    public function mount()
    {
        // Fetch data from database (Example: Card Charges by Type)
        $charges = DB::table('ra_issues')
            ->select('type','currency', DB::raw('SUM(detected) as total_detected'),
            DB::raw('SUM(prevented) as total_prevented'),DB::raw('SUM(recovered) as total_recovered'))
            ->groupBy('type')
            ->get();

        // Assign values to labels and data
        foreach ($charges as $charge) {
            $this->labels[] = $charge->type;
            $this->data[] = $charge->total_detected;
        }
    }


    public function updateChart()
    {
        $this->dispatchBrowserEvent('chartUpdated', [
            'labels' => $this->labels,
            'data' => $this->data
        ]);
    }

    public function render()
    {
        return view('livewire.pie-chart');
    }


}
