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
        $old_values = json_decode($result->old_values);
        $new_values = '';
        if ($result->action == DualControl::EDIT)
        {
            $new_values = json_decode($result->new_values);
        }
//        $data = $this->getAllDetails($result->controllable_type, encrypt($result->controllable_type_id));
        return view('settings.dual-control-activities.show', compact('result','old_values', 'new_values'));
    }


}
