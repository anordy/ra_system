<?php

namespace App\Http\Controllers;

use App\Enum\CustomMessage;
use App\Enum\GeneralConstant;
use App\Models\SystemSetting;
use App\Models\WithholdingAgent;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PDF;

class WithholdingAgentController extends Controller
{
    public function index()
    {
        if (!Gate::allows('withholding-agents-view')) {
            abort(403);
        }
        return view('withholding-agent.index');
    }

    public function activeRequest()
    {
        if (!Gate::allows('withholding-agents-view')) {
            abort(403);
        }
        return view('withholding-agent.active');
    }

    public function view($id)
    {
        if (!Gate::allows('withholding-agents-view')) {
            abort(403);
        }
        return view('withholding-agent.view', compact('id'));
    }
    public function show($id)
    {
        if (!Gate::allows('withholding-agents-view')) {
            abort(403);
        }
        return view('withholding-agent.show', compact('id'));
    }

    public function registration()
    {
        if (!Gate::allows('withholding-agents-registration')) {
            abort(403);
        }
        return view('withholding-agent.registration');
    }

    public function certificate($id)
    {
        try {
            $id = decrypt($id);

            $whagent = WithholdingAgent::with(['latestResponsiblePerson'])
                ->findOrFail($id, ['id', 'institution_name', 'institution_place', 'wa_number']);

            $url = env('TAXPAYER_URL') . route('qrcode-check.withholding-agent.certificate',  base64_encode(strval($id)), 0);

            $result = Builder::create()
                ->writer(new PngWriter())
                ->writerOptions([SvgWriter::WRITER_OPTION_EXCLUDE_XML_DECLARATION => false])
                ->data($url)
                ->encoding(new Encoding(GeneralConstant::UTF_8))
                ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->size(GeneralConstant::QR_CODE_SIZE)
                ->margin(GeneralConstant::ZERO_INT)
                ->logoPath(public_path('/images/logo.png'))
                ->logoResizeToHeight(GeneralConstant::QR_CODE_LOGO_SIZE)
                ->logoResizeToWidth(GeneralConstant::QR_CODE_LOGO_SIZE)
                ->labelText('')
                ->labelAlignment(new LabelAlignmentCenter())
                ->build();

            header('Content-Type: ' . $result->getMimeType());

            $signature = getSignature($whagent);

            if (!$signature) {
                session()->flash('error', 'Signature for this time is not configured');
                return back();
            }

            $signaturePath = $signature->image;
            $commissinerFullName = $signature->name;
            $title = $signature->title;

            $pdf = PDF::loadView('withholding-agent.certificate', compact('whagent', 'dataUri', 'signaturePath', 'commissinerFullName', 'title'));
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOption(['dpi' => GeneralConstant::DPI, 'defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);

            return $pdf->stream();
        } catch (\Exception $exception) {
            Log::error('WITHHOLDING-AGENT-CONTROLLER-CERTIFICATE', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

    public function getWithholdingAgentFile($agentId, $type)
    {
        try {
            $withholding_agent = WithholdingAgent::find(decrypt($agentId));

            if ($type == GeneralConstant::APPROVAL_LETTER) {
                return Storage::disk('local')->response($withholding_agent->approval_letter);
            }

            return abort(404);
        } catch (\Exception $exception) {
            Log::error('WITHHOLDING-AGENT-CONTROLLER-GET-WITHHOLDING-AGENT-FILE', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }
}
