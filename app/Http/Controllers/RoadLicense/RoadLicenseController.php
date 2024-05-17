<?php

namespace App\Http\Controllers\RoadLicense;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\RoadLicense\RoadLicense;
use Illuminate\Support\Facades\Log;

class RoadLicenseController extends Controller
{

    public function show($id) {
        try {
            $roadLicense = RoadLicense::with(['registration', 'taxpayer'])
                ->findOrFail(decrypt($id), ['id', 'mvr_registration_id', 'taxpayer_id', 'category_id', 'passengers_no', 'capacity', 'inspection_date', 'issued_date', 'expire_date', 'urn', 'marking', 'pass_mark', 'created_at', 'updated_at', 'status']);
            return view('road-license.show', compact('roadLicense'));
        } catch (\Exception $exception) {
            Log::error('ROAD-LICENSE-CONTROLLER-SHOW', [$exception]);
            session()->flash('error', CustomMessage::ERROR);
            return redirect()->back();
        }
    }

    public function index() {
        return view('road-license.index');
    }
}
