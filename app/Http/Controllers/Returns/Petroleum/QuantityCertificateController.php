<?php

namespace App\Http\Controllers\Returns\Petroleum;

use App\Enum\CustomMessage;
use App\Enum\QuantityCertificateStatus;
use App\Http\Controllers\Controller;
use App\Models\Returns\Petroleum\QuantityCertificate;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PDF;

class QuantityCertificateController extends Controller
{

    public function index()
    {
        if (!Gate::allows('certificate-of-quantity-view')) {
            abort(403);
        }

        return view('returns.petroleum.quantity_certificate.index');
    }

    public function create()
    {
        if (!Gate::allows('certificate-of-quantity-create')) {
            abort(403);
        }

        return view('returns.petroleum.quantity_certificate.create');
    }

    public function edit($id)
    {
        $certificate = QuantityCertificate::findOrFail(decrypt($id), ['status']);

        if (($certificate->status ?? '') == QuantityCertificateStatus::FILLED) {
            abort(403);
        }

        return view('returns.petroleum.quantity_certificate.edit', compact('id'));
    }

    public function show($id)
    {
        if (!Gate::allows('certificate-of-quantity-view')) {
            abort(403);
        }

        return view('returns.petroleum.quantity_certificate.show', compact('id'));
    }


    public function certificate($id)
    {
        if (!Gate::allows('certificate-of-quantity-view')) {
            abort(403);
        }

        try {

            $data = QuantityCertificate::with('business')->findOrFail(decrypt($id), ['id', 'business_id', 'location_id', 'ship', 'port', 'voyage_no', 'ascertained', 'download_count', 'created_by', 'status', 'created_at', 'updated_at', 'certificate_no', 'quantity_certificate_attachment']);

            $addressInfo = [
              'operatingOffice' => $this->getAddressInfo(SystemSetting::OPERATING_OFFICE),
              'email' => $this->getAddressInfo(SystemSetting::EMAIL),
              'tel' => $this->getAddressInfo(SystemSetting::TEL),
              'fax' => $this->getAddressInfo(SystemSetting::FAX),
              'poBox' => $this->getAddressInfo(SystemSetting::PO_BOX),
              'institutionName' => $this->getAddressInfo(SystemSetting::INSTITUTION_NAME),
              'institutionLocation' => $this->getAddressInfo(SystemSetting::INSTITUTION_LOCATION),
              'institutionWebsite' => $this->getAddressInfo(SystemSetting::INSTITUTION_WEBSITE),
            ];

            if (($data->status ?? '') == QuantityCertificateStatus::FILLED) {
                abort(403);
            }

            $view = view('returns.petroleum.quantity_certificate.pdf', compact('data', 'addressInfo'));
            $html = $view->render();
            $pdf = PDF::loadHTML($html);
            return $pdf->stream('id_' . time() . '.pdf');

        } catch (\Exception $exception) {
            Log::error('RETURNS-PETROLEUM-QUANTITY-CERTIFICATE-CONTROLLER-CERTIFICATE', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

    public function getAttachedCertificateFile($certificateId)
    {
        if (!Gate::allows('certificate-of-quantity-view')) {
            abort(403);
        }

        try {

            $file = QuantityCertificate::findOrFail(decrypt($certificateId), ['quantity_certificate_attachment']);

            if ($file) {
                return Storage::disk('local')->response($file->quantity_certificate_attachment);
            }

            return abort(404);

        } catch (\Exception $exception) {
            Log::error('RETURNS-PETROLEUM-QUANTITY-CERTIFICATE-CONTROLLER-GET-ATTACHED-CERTIFICATE-FILE', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

    private function getAddressInfo($infoType) {
        try {
              return SystemSetting::select('value')->where('code', $infoType)->first()->value ?? 'N/A';
        } catch (\Exception $exception) {
            Log::error('RETURNS-PETROLEUM-QUANTITY-CERTIFICATE-CONTROLLER-GET-ADDRESS-INFO', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }


}
