<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\DualControl;
use App\Models\Role;
use App\Traits\DualControlActivityTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DualControlActivityController extends Controller
{
    use DualControlActivityTrait;

    public function index()
    {
        if (!Gate::allows('setting-dual-control-activities-view')) {
            abort(403);
        }
        return view('dual-control-activities.index');
    }

    public function show($id)
    {
        if (!Gate::allows('setting-dual-control-activities-view')) {
            abort(403);
        }
        $result = DualControl::findOrFail(decrypt($id));
        $data = $this->getAllDetails($result->controllable_type, encrypt($result->controllable_type_id));
        $old_values = json_decode($result->old_values);
        $new_values = '';
        $report_to_old = '';
        $report_to_new = '';
        if ($result->action == DualControl::EDIT) {
            $new_values = json_decode($result->new_values);
            if ($result->controllable_type == DualControl::ROLE) {
                $report_to_old = $this->getRoleName($old_values->report_to);
                $report_to_new = $this->getRoleName($new_values->report_to);
            }
        }else{
            if ($result->controllable_type == DualControl::ROLE) {
                $report_to_old = $this->getRoleName($data->report_to);
            }
        }

        return view('dual-control-activities.show', compact('result', 'data', 'old_values', 'new_values', 'report_to_new', 'report_to_old'));
    }

    public function getRoleName($id)
    {
        if (!empty($id))
        {
            $role = Role::query()->findOrFail($id);
            return $role->name;
        }
    }

    public function configure()
    {
//        dd('configuration will be here');
        return view('dual-control-activities.configure');
    }


}
