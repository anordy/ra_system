<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\DualControl;
use App\Traits\DualControlActivityTrait;
use Illuminate\Http\Request;

class DualControlActivityController extends Controller
{
    use DualControlActivityTrait;

    public function index()
    {
        return view('settings.dual-control-activities.index');
    }

    public function show($id)
    {
        $result = DualControl::findOrFail(decrypt($id));
        $data = $this->getAllDetails($result->controllable_type, encrypt($result->controllable_type_id));
        return view('settings.dual-control-activities.show', compact('result', 'data'));
    }


}
