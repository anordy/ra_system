<?php

namespace App\Http\Controllers\PropertyTax;

use App\Http\Controllers\Controller;
use App\Models\PropertyTax\Property;
use App\Models\PropertyTax\PropertyPayment;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Gate;
use PDF;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;

class PropertyTaxController extends Controller
{

    public function index() {
        if (!Gate::allows('properties-registrations')) {
            abort(403);
        }
        return view('property-tax.index');
    }

    public function show(string $id) {
        if (!Gate::allows('properties-registrations')) {
            abort(403);
        }
        $property = Property::findOrFail(decrypt($id));
        return view('property-tax.show', compact('property'));
    }

    public function nextBills() {
        if (!Gate::allows('next-bills-preview')) {
            abort(403);
        }
        return view('property-tax.next-financial-year-bills');
    }

    public function paidPayments() {
        if (!Gate::allows('properties-registrations')) {
            abort(403);
        }
        return view('property-tax.payment.paid');
    }

    public function unpaidPayments() {
        if (!Gate::allows('properties-registrations')) {
            abort(403);
        }
        return view('property-tax.payment.unpaid');
    }

    public function nonGenBills() {
        if (!Gate::allows('properties-registrations')) {
            abort(403);
        }
        return view('property-tax.payment.non-gen-bills');
    }

    public function getBill($id){
        if (!Gate::allows('properties-registrations')) {
            abort(403);
        }
        $propertyPayment = PropertyPayment::findOrFail(decrypt($id));

        $url = env('TAXPAYER_URL');

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


        $pdf = PDF::loadView('property-tax.bill', compact('propertyPayment','dataUri', 'signaturePath', 'commissinerFullName'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);

        return $pdf->stream();

    }

    public function getNotice($id){
        if (!Gate::allows('properties-registrations')) {
            abort(403);
        }

        $propertyPayment = PropertyPayment::findOrFail(decrypt($id));

        $url = env('TAXPAYER_URL');

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


        $pdf = PDF::loadView('property-tax.notice', compact('propertyPayment','dataUri', 'signaturePath', 'commissinerFullName'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);

        return $pdf->stream();

    }
}