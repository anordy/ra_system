<?php

namespace App\Http\Controllers;
use App\Models\SystemSetting;
use App\Models\WithholdingAgent;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Facades\Gate;
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

    public function certificate($id){
        $id = decrypt($id);
        $whagent = WithholdingAgent::findOrFail($id);

        $url = env('TAXPAYER_URL') . route('qrcode-check.withholding-agent.certificate',  base64_encode(strval($id)), 0);

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([SvgWriter::WRITER_OPTION_EXCLUDE_XML_DECLARATION => false])
            ->data($url)
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

        $signaturePath = SystemSetting::certificatePath();
        $commissinerFullName = SystemSetting::commissinerFullName();

        $pdf = PDF::loadView('withholding-agent.certificate', compact('whagent', 'dataUri', 'signaturePath', 'commissinerFullName'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);

        return $pdf->stream();
  
    }

    public function getWithholdingAgentFile($agentId, $type)
    {

        $withholding_agent = WithholdingAgent::find(decrypt($agentId));
        
        if ($type == 'approval_letter') {
            return Storage::disk('local')->response($withholding_agent->approval_letter);
        }

        return abort(404);
    }

}
