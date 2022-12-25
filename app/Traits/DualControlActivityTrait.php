<?php

namespace App\Traits;

use App\Models\DualControl;
use App\Models\Role;
use App\Models\TaPaymentConfiguration;
use App\Models\User;
use Exception;
use Carbon\Carbon;
use App\Models\Audit;
use App\Models\SystemSetting;
use App\Models\SystemSettingCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait DualControlActivityTrait
{
    public function triggerDualControl(
        $model,
        $modelId,
        $action,
        $action_detail,
        $old_values = null,
        $edited_values = null)
    {
        $payload = [
            'controllable_type' => $model,
            'controllable_type_id' => $modelId,
            'action' => $action,
            'action_detail' => $action_detail,
            'old_values' => $old_values,
            'new_values' => $edited_values,
            'create_by_id' => Auth::id(),
            'status' => 'pending',
        ];
        DualControl::updateOrCreate($payload);
        if ($action != DualControl::ADD)
        {
            $data = $model::findOrFail($modelId);
            $data->update(['is_approved' => DualControl::NOT_APPROVED]);
        }
    }

    public function getModule($model)
    {
        switch ($model) {
            case DualControl::USER:
                return 'User';
                break;

            case DualControl::ROLE:
                return 'Role';
                break;

            case DualControl::CONSULTANT_FEE:
                return 'Tax Consultant Fee';
                break;
            case DualControl::SYSTEM_SETTING_CONFIG:
                return 'System Setting Configuration';
                break;
            case DualControl::SYSTEM_SETTING_CATEGORY:
                return 'System Setting Category Configuration';
                break;

            default:
                abort(404);
        }
    }

    public function getAllDetails($model, $modelId)
    {
        $modelId = decrypt($modelId);
        $data = $model::findOrFail($modelId);
        return $data;
    }

    public function updateControllable($data, $status)
    {
        $update = $data->controllable_type::findOrFail($data->controllable_type_id);
        if ($data->action != DualControl::ADD)
        {
            $payload = json_decode($data->old_values);
            $payload = (array)$payload;
            $payload = array_merge($payload, ['is_approved' => DualControl::APPROVE]);
            $update->update((array)$payload);
        }
        else
        {
            $update->update(['is_approved' => $status]);
        }

    }

}
