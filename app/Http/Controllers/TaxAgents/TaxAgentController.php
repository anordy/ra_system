<?php

namespace App\Http\Controllers\TaxAgents;

use App\Http\Controllers\Controller;
use App\Models\RenewTaxAgentRequest;
use App\Models\TaPaymentConfiguration;
use App\Models\TaxAgent;
use Carbon\Carbon;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use PDF;

class TaxAgentController extends Controller
{

    public function index()
    {
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
        $id = Crypt::decrypt($id); // todo: suggestion: unless you want the value, better pass encrypted
        $agent = TaxAgent::query()->findOrfail($id); // todo: handle exception 404-customize to have action fallback
        return view('taxagents.active-agent-show', compact('agent', 'id'));
    }

//    todo: all finds should check for nullable
    public function certificate($id)
    {
        if (!Gate::allows('active-tax-consultant-view')) {
            abort(403);
        }
        $id = decrypt($id);
        $taxagent = TaxAgent::with('taxpayer')->find($id);
        if ($taxagent->is_first_application == 1)
        {
            $start_date = $taxagent->app_first_date;
            $end_date = $taxagent->app_expire_date;
            $start = date('d', strtotime($start_date));
            $end = date('d', strtotime($end_date));
            $diff = Carbon::create($end_date)->diffInYears($start_date);

        }
        else
        {
            $renew = $taxagent->request->first();
            $start_date = $renew->renew_first_date;
            $end_date = $renew->renew_expire_date;
            $start = date('d', strtotime($start_date));
            $end = date('d', strtotime($end_date));
            $diff = Carbon::create($end_date)->diffInYears($start_date);
        }

        $word = $diff > 1 ? 'years' : 'year';

        $superStart = $this->sup($start);
        $superEnd = $this->sup($end);

        $code = 'Name: ' . $taxagent->taxpayer->fullName . ", " .
            'Location: ' . $taxagent->district->name . ', ' . $taxagent->region->name . ", " .
            'Period: ' . $diff.' '.$word .
            'From: ' . "{$start_date}" . ", " .
            'To: ' . "{$end_date}" . ", " .
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

        $pdf = PDF::loadView('taxagents.certificate', compact('taxagent', 'start_date', 'end_date', 'superStart', 'superEnd', 'diff', 'word', 'dataUri'));
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
        $agent = TaxAgent::query()->findOrfail($id); // todo: handle exception
        $fee = TaPaymentConfiguration::query()->select('id', 'amount', 'category', 'is_citizen')
            ->where('category', 'Registration Fee')
            ->where('is_citizen', $agent->taxpayer->is_citizen)
            ->first();
        return view('taxagents.verification-request-agent-show', compact('agent', 'id', 'fee'));
    }

    public function renewal()
    {
        if (!Gate::allows('tax-consultant-renewal-requests-view')) {
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
        $renew = RenewTaxAgentRequest::query()->findOrFail($id);
        $agent = $renew->tax_agent;
        $fee = TaPaymentConfiguration::query()->select('id', 'amount', 'category', 'is_citizen')
            ->where('category', 'Renewal Fee')
            ->where('is_citizen', $agent->taxpayer->is_citizen)
            ->first();
        return view('taxagents.renew.show', compact('renew', 'fee'));
    }

    public function viewConsultantRenewRequests($id)
    {
        $requests = RenewTaxAgentRequest::where('tax_agent_id', decrypt($id))->orderByDesc('id')->get();
        $agent = TaxAgent::query()->findOrfail(decrypt($id));
        if (!empty($agent))
        {
            $consultant = $agent->taxpayer->first_name.' '.$agent->taxpayer->middle_name.' '.$agent->taxpayer->last_name;
        }
        return view('taxagents.consultant-renew-requests.index', compact('requests', 'consultant', 'id'));
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
        if (!Gate::allows('active-tax-consultant-view')) {
            abort(403);
        }
        $a = [1, 21, 31];
        $b = [2, 22];
        $c = [3, 23];
        $date = [];
        for ($x = 4; $x <= 20; $x++) {
            $date[] = $x;
        }
        for ($y = 24; $y <= 30; $y++) {
            $date[] = $y;
        }
        if (in_array($app_date, $date)) {
            return 'th';
        } elseif (in_array($app_date, $c)) {
            return 'rd';
        } elseif (in_array($app_date, $b)) {
            return 'nd';
        } else {
            return 'st';
        }
    }
}
