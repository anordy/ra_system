<?php

namespace App\Http\Controllers;
use App\Models\WithholdingAgent;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Facades\Gate;
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

    public function view($id)
    {
        if (!Gate::allows('withholding-agents-view')) {
            abort(403);
        }
        return view('withholding-agent.view', compact('id'));
    }

    public function registration()
    {
        if (!Gate::allows('withholding-agents-registration')) {
            abort(403);
        }
        return view('withholding-agent.registration');
    }

    public function certificate($id){
        $whagent = WithholdingAgent::findOrFail(decrypt($id));

        $code = [
            'Institution Name' => $whagent->institution_name,
            'Institution Place' => $whagent->institution_place,
            'Agency No.' => $whagent->wa_number
        ];

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([SvgWriter::WRITER_OPTION_EXCLUDE_XML_DECLARATION => false])
            ->data(json_encode($code))
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

        $pdf = PDF::loadView('withholding-agent.certificate', compact('whagent', 'dataUri'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->stream();
  
    }

}
