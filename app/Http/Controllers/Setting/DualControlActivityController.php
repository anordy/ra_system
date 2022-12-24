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
        $edited_values = '';
        $result = DualControl::findOrFail(decrypt($id));
        if ($result->action == DualControl::EDIT)
        {
            $edited_values = json_decode($result->edited_values);
        }
        $data = $this->getAllDetails($result->controllable_type, encrypt($result->controllable_type_id));
        return view('settings.dual-control-activities.show', compact('result', 'data', 'edited_values'));
    }


}
