<?php

namespace App\Http\Livewire\Reports\Registrations;

use Livewire\Component;

class RegistrationReport extends Component
{
    public $optionReportTypes;
    public $report_type_id;

    public function mount(){
        $this->optionReportTypes = [
            'Registered-Businesses'=>'Registered Businesses',
            // 'Registered Taxpayers',
            // 'Taxpayers with changed Tax types',
            // 'Inactive Taxpayers',
            // 'Deregistered Taxpayers',
            // 'Taxpayers By Tax types and Tax Regions',
            // 'Taxpayers Registered by Period',
            // 'Taxpayer History',
            // 'Monthly Taxpayer Registrations',
        ];
    }

    public function preview(){
        dd('Preview Report');
    }

    public function exportPdf(){
        dd('Export PDF');
    }

    public function exportExcel(){
        dd('Export Excel');
    }

    public function render()
    {
        return view('livewire.reports.registrations.registration-report');
    }
}
