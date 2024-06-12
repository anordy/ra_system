<?php

namespace App\Http\Controllers\LandLease;

use App\Http\Controllers\Controller;
use App\Models\LandLease;
use App\Models\LandLeaseAgent;
use App\Models\LeasePayment;
use App\Traits\LeasePaymentReportTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;
use PDF;

use Illuminate\Support\Facades\Gate;

class LandLeaseController extends Controller
{
    use LeasePaymentReportTrait;
    //

    public function index()
    {
        if (!Gate::allows('land-lease-view')) {
            abort(403);
        }
        return view('land-lease.land-lease-list');
    }

    public function view($id)
    {
        if (!Gate::allows('land-lease-view')) {
            abort(403);
        }
        return view('land-lease.view-land-lease', compact('id'));
    }

    public function viewLeasePayment($id)
    {
        if (!Gate::allows('land-lease-edit')) {
            abort(403);
        }
        return view('land-lease.view-lease-payment', compact('id'));
    }

    public function getAgreementDocument($path)
    {
        return Storage::disk('local')->response(decrypt($path));
    }

    public function generateReport()
    {
        if (!Gate::allows('land-lease-generate-report')) {
            abort(403);
        }
        return view('land-lease.generate-report');
    }

    public function paymentReport()
    {
        return view('land-lease.payment-report');
    }

    public function createAgent()
    {
        if (!Gate::allows('land-lease-agent-view')) {
            abort(403);
        }
        return view('land-lease.agent-create');
    }

    public function agentsList(){
        if (!Gate::allows('land-lease-agent-view')) {
            abort(403);
        }
        return view('land-lease.agents');
    }

    public function agentStatusChange($payload)
    {
        if (!Gate::allows('land-lease-agent-view')) {
            abort(403);
        }
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
            Log::error($e);
            session()->flash('error', 'Status failed to change');
            return redirect()->back();
        }
        
    }

    public function downloadLandLeaseReportPdf($datesJson)
    {

        if (!Gate::allows('land-lease-generate-report')) {
            abort(403);
        }

        $data = decrypt($datesJson);
        $dates = $data['dates'];
        $taxpayer_id = $data['taxpayer_id'];

        if ($dates == []) {
            $landLeases = LandLease::query()->orderBy('created_at', 'asc');
        } elseif ($dates['startDate'] == null || $dates['endDate'] == null) {
            $landLeases = LandLease::query()->orderBy('created_at', 'asc');
        } else {
            $landLeases = LandLease::query()->whereBetween('created_at', [$dates['startDate'], $dates['endDate']])->orderBy('created_at', 'asc');
        }

        if ($taxpayer_id) {
            $landLeases = clone $landLeases->where('land_leases.taxpayer_id', $taxpayer_id);
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


    public function downloadLandLeasePaymentReportPdf($parameter)
    {
        if (!Gate::allows('land-lease-generate-report')) {
            abort(403);
        }
        
        $data = json_decode(decrypt($parameter),true);
        $dates = $data['dates'];
        $status = $data['status'];
        $date_type = $data['date_type'];
        $taxpayer_id = $data['taxpayer_id'];

        if ($dates == []) {
            $leasePayments = LeasePayment::query()->orderBy('created_at', 'asc');
        } elseif ($dates['startDate'] == null || $dates['endDate'] == null) {
            $leasePayments = LeasePayment::query()->orderBy('created_at', 'asc');
        } else {

            if ($date_type == 'payment_month') {
                $months = $this->getMonthList($dates);
                $years = $this->getYearList($dates);
                $leasePayments = LeasePayment::query()
                ->leftJoin('land_leases', 'land_leases.id', 'lease_payments.land_lease_id')
                ->leftJoin('financial_years', 'financial_years.id', 'lease_payments.financial_year_id')
                ->whereIn("land_leases.{$this->date_type}", $months)
                ->whereIn("financial_years.code", $years);

            } elseif ($date_type == 'payment_year') {
                $years = $this->getYearList($dates);
                $leasePayments = LeasePayment::query()
                ->leftJoin('financial_years', 'financial_years.id', 'lease_payments.financial_year_id')
                ->whereIn("financial_years.code", $years);

            }else {
                $leasePayments = LeasePayment::query()->whereBetween("lease_payments.{$date_type}", [$dates['startDate'], $dates['endDate']]);
            }

        }

        if ($status) {
            $leasePayments = clone $leasePayments->where('lease_payments.status', $status);
        }

        if ($taxpayer_id) {
            $leasePayments = clone $leasePayments->where('lease_payments.taxpayer_id', $taxpayer_id);
        }

        $leasePayments = $leasePayments->get();
        $from = \Carbon\Carbon::parse($dates['startDate']); 
        $to = \Carbon\Carbon::parse($dates['endDate']); 
        $startDate= $from->format('Y-m-d');
        $endDate = $to->format('Y-m-d');
        $pdf = PDF::loadView('exports.land-lease.pdf.lease-payment-report',compact('leasePayments','startDate','endDate'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download('Land Leases applications FROM ' . $dates['from'] . ' TO ' . $dates['to'] . '.pdf');
    }

    public function register()
    {
        return view('land-lease.register-land-lease');
    }

    public function assignTaxpayer($id)
    {
        return view("land-lease.assign-taxpayer",compact('id'));
    }
    public function taxpayerView($id)
    {
        return view('land-lease.taxpayer-land-lease-view', compact('id'));
    }
    public function edit($id)
    {
        return view("land-lease.land-lease-edit",compact('id'));
    }
}
