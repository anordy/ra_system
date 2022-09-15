<?php

namespace App\Http\Controllers\LandLease;

use App\Http\Controllers\Controller;
use App\Models\LandLease;
use App\Models\LandLeaseAgent;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;
use PDF;

class LandLeaseController extends Controller
{
    //

    public function index()
    {
        return view('land-lease.land-lease-list');
    }

    public function view($id)
    {
        return view('land-lease.view-land-lease', compact('id'));
    }

    public function viewLeasePayment($id)
    {
        return view('land-lease.view-lease-payment', compact('id'));
    }

    public function getAgreementDocument($path)
    {
        return Storage::disk('local-admin')->response(decrypt($path));
    }

    public function generateReport()
    {
        return view('land-lease.generate-report');
    }

    public function createAgent()
    {
        return view('land-lease.agent-create');
    }

    public function agentsList(){

        return view('land-lease.agents');
    }

    public function agentStatusChange($payload)
    {
        $data = json_decode(decrypt($payload),true);
        try {
            if($data['active']){
                LandLeaseAgent::where('id',$data['id'])->update(['status'=>'ACTIVE']);
            }else{
                LandLeaseAgent::where('id',$data['id'])->update(['status'=>'INACTIVE']);
            }
            session()->flash('success', 'Status Changes');
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            session()->flash('error', 'Status failed to change');
            return redirect()->back();
        }
        
    }

    public function downloadLandLeaseReportPdf($datesJson)
    {
        $dates = json_decode(decrypt($datesJson),true);
        if ($dates == []) {
            $landLeases = LandLease::query()->orderBy('created_at', 'asc');
        } elseif ($dates['startDate'] == null || $dates['endDate'] == null) {
            $landLeases = LandLease::query()->orderBy('created_at', 'asc');
        } else {
            $landLeases = LandLease::query()->whereBetween('created_at', [$dates['startDate'], $dates['endDate']])->orderBy('created_at', 'asc');
        }
        $landLeases = $landLeases->get();
        $from = \Carbon\Carbon::parse($dates['startDate']); 
        $to = \Carbon\Carbon::parse($dates['endDate']); 
        $startDate= $from->format('Y-m-d');
        $endDate = $to->format('Y-m-d');
        $pdf = PDF::loadView('exports.land-lease.pdf.land-lease-report',compact('landLeases','startDate','endDate'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download('Land Leases applications FROM ' . $dates['from'] . ' TO ' . $dates['to'] . '.pdf');
    }
}
