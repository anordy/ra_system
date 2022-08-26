<?php

namespace App\Http\Controllers\LandLease;

use App\Http\Controllers\Controller;
use App\Models\LandLeaseAgent;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;

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
}
