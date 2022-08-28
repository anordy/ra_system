<?php

namespace App\Http\Controllers\TaxAgents;

use App\Http\Controllers\Controller;
use App\Models\TaPaymentConfiguration;
use App\Models\TaxAgent;
use App\Models\TaxAgentAcademicQualification;
use App\Models\TaxAgentProfessionals;
use App\Models\TaxAgentTrainingExperience;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use PDF;

class TaxAgentController extends Controller
{

	public function index(){
        if (!Gate::allows('tax-consultant-registration-view')) {
            abort(403);
        }
		return view('taxagents.index');
	}

	public function activeAgents()
	{
        if (!Gate::allows('active-tax-consultant-view')) {
            abort(403);
        }
		return view('taxagents.activeTaxagents');
	}

	public function showActiveAgent($id)
	{
        if (!Gate::allows('active-tax-consultant-view')) {
            abort(403);
        }
		$id = Crypt::decrypt($id);
		$agent = TaxAgent::query()->findOrfail($id);
		return view('taxagents.active-agent-show', compact('agent', 'id'));
	}

    public function certificate($id){
        if (!Gate::allows('active-tax-consultant-view')) {
            abort(403);
        }
        $id = decrypt($id);
        $taxagent = TaxAgent::with('taxpayer')->find($id);
        $start = date('d', strtotime($taxagent->app_first_date));
        $end = date('d', strtotime($taxagent->app_expire_date));
        $superStart = $this->sup($start);
        $superEnd = $this->sup($end);

        $code = 'Name: ' . $taxagent->taxpayer->fullName . ", " .
            'Location: ' . $taxagent->district->name.', '.$taxagent->region->name . ", " .
            'Period: 1 Year'.
            'From: ' . "{$start}" . ", " .
            'To: ' . "{$end}" . ", " .
            'https://uat.ubx.co.tz:8888/zrb_client/public/login';

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([SvgWriter::WRITER_OPTION_EXCLUDE_XML_DECLARATION => false])
            ->data($code)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(207)
            ->margin(0)
            ->logoPath(public_path('/images/logo.png'))
            ->logoResizeToHeight(36)
            ->logoResizeToWidth(36)
            ->labelText('')
            ->labelAlignment(new LabelAlignmentCenter())
            ->build();

        header('Content-Type: ' . $result->getMimeType());

        $dataUri = $result->getDataUri();

        $pdf = PDF::loadView('taxagents.certificate', compact('taxagent', 'superStart','superEnd', 'dataUri'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->stream();

    }

	public function showAgentRequest($id)
	{
        if (!Gate::allows('tax-consultant-registration-view')) {
            abort(403);
        }
		$id = Crypt::decrypt($id);
		$agent = TaxAgent::query()->findOrfail($id);

		return view('taxagents.request-agent-show', compact('agent', 'id'));
	}

	public function showVerificationAgentRequest($id)
	{
        if (!Gate::allows('tax-consultant-registration-view')) {
            abort(403);
        }
		$id = Crypt::decrypt($id);
		$agent = TaxAgent::query()->findOrfail($id);
        $fee = DB::table('ta_payment_configurations')
            ->where('category', '=', 'registration fee')->first();
		return view('taxagents.verification-request-agent-show', compact('agent', 'id', 'fee'));
	}

	public function renewal()
	{
        if (Gate::allows('tax-consultant-renewal-requests-view')) {
            abort(403);
        }
		return view('taxagents.renew.renewalRequests');
	}

    public function renewalShow($id)
    {
        if (!Gate::allows('tax-consultant-renewal-requests-view')) {
            abort(403);
        }
        $id = decrypt($id);
        $agent = TaxAgent::query()->findOrfail($id);
        $fee = DB::table('ta_payment_configurations')
            ->where('category', '=', 'renewal fee')->first();
        return view('taxagents.renew.show', compact('agent','fee'));
    }

	public function fee()
	{
    if (!Gate::allows('tax-consultant-fee-configuration-view')) {
        abort(403);
    }
		return view('taxagents.fee-config');
	}

    public function sup($app_date)
    {
        $a = [1,21,31];
        $b= [2,22];
        $c = [3,23];
        $date=[];
        for ($x=4; $x<=20; $x++)
        {
            $date[]=$x;
        }
        for ($y=24; $y<=30; $y++)
        {
            $date[]=$y;
        }
        if (in_array($app_date,$date))
        {
            return 'th';
        }

        elseif (in_array($app_date,$c))
        {
            return 'rd';
        }
        elseif (in_array($app_date,$b))
        {
            return 'nd';
        }
        else
        {
            return 'st';
        }
    }
}
