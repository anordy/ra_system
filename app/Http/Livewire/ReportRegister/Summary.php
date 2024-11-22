<?php

namespace App\Http\Livewire\ReportRegister;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Summary extends Component
{
    public $taxpayerSubs = [], $stats = [];
    public $staffSubs = [], $startDate, $endDate;

    public function mount()
    {
        $this->startDate = now()->subDays(7)->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->filter();
    }

    public function search() {
        $this->filter();
    }

    public function filter() {
        $this->taxpayerSubs = DB::select("
                SELECT RSC.NAME AS name, COUNT(DISTINCT RG.ID) AS count
                FROM RG_REGISTERS RG
                LEFT JOIN RG_SUB_CATEGORIES RSC ON RG.RG_SUB_CATEGORY_ID = RSC.ID
                WHERE RG.REGISTER_TYPE = 1
                AND RG.REQUESTER_TYPE = 1
                AND RG.created_at BETWEEN '{$this->startDate}' AND '{$this->endDate}'
                GROUP BY RSC.NAME 
                ORDER BY count DESC
                FETCH FIRST 5 ROWS ONLY
        ");

        $this->staffSubs = DB::select("
                SELECT RSC.NAME AS name, COUNT(DISTINCT RG.ID) AS count
                FROM RG_REGISTERS RG
                LEFT JOIN RG_SUB_CATEGORIES RSC ON RG.RG_SUB_CATEGORY_ID = RSC.ID
                WHERE RG.REGISTER_TYPE = 1
                AND RG.REQUESTER_TYPE = 2
                AND RG.created_at BETWEEN '{$this->startDate}' AND '{$this->endDate}'
                GROUP BY RSC.NAME
                ORDER BY count DESC
                FETCH FIRST 5 ROWS ONLY
        ");


        $this->stats = DB::select("
                SELECT 
                    COUNT(CASE WHEN requester_type = 1 THEN 1 END) AS totalTaxpayerIncidents,
                    COUNT(CASE WHEN requester_type = 1 AND status != 'resolved' THEN 1 END) AS totalPendingTaxpayerIncidents,
                    COUNT(CASE WHEN requester_type = 1 AND status = 'resolved' THEN 1 END) AS totalClosedTaxpayerIncidents,
                    COUNT(CASE WHEN requester_type = 1 AND is_breached = 1 THEN 1 END) AS totalTaxpayerBreachedIncidents,
                    COUNT(CASE WHEN requester_type = 1 AND is_breached = 0 AND status = 'resolved' THEN 1 END) AS totalTaxpayerInTimeClosedIncidents,
                    COUNT(CASE WHEN requester_type = 1 AND is_breached = 1 AND status = 'resolved' THEN 1 END) AS totalTaxpayerLateClosedIncidents,
                
                    COUNT(CASE WHEN requester_type = 2 THEN 1 END) AS totalStaffIncidents,
                    COUNT(CASE WHEN requester_type = 2 AND status != 'resolved' THEN 1 END) AS totalPendingStaffIncidents,
                    COUNT(CASE WHEN requester_type = 2 AND status = 'resolved' THEN 1 END) AS totalClosedStaffIncidents,
                    COUNT(CASE WHEN requester_type = 2 AND is_breached = 1 THEN 1 END) AS totalStaffBreachedIncidents,
                    COUNT(CASE WHEN requester_type = 2 AND is_breached = 0 AND status = 'resolved' THEN 1 END) AS totalStaffInTimeClosedIncidents,
                    COUNT(CASE WHEN requester_type = 2 AND is_breached = 1 AND status = 'resolved' THEN 1 END) AS totalStaffLateClosedIncidents
                FROM RG_REGISTERS
                WHERE register_type = 1
                AND created_at BETWEEN '{$this->startDate}' AND '{$this->endDate}'
        ");
    }


    public function render()
    {
        return view('livewire.report-register.summary');
    }
}
