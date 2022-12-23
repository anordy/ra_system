<?php

namespace App\Traits;

use App\Models\DualControl;
use App\Models\Role;
use App\Models\TaPaymentConfiguration;
use App\Models\User;
use Exception;
use Carbon\Carbon;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait DualControlActivityTrait
{
    public function triggerDualControl($model, $modelId, $action, $action_detail, $edited_values=null)
    {
        $payload = [
            'controllable_type' => $model,
            'controllable_type_id' => $modelId,
            'action' =>$action,
            'action_detail' => $action_detail,
            'edited_values' => $edited_values,
            'create_by_id' => Auth::id(),
            'status' => 'pending'
        ];
        DualControl::updateOrCreate($payload);
    }

    public function getModule($model)
    {
        switch ($model) {
            case User::class:
                return 'User';
                break;

            case Role::class:
                return 'Role';
                break;

            case TaPaymentConfiguration::class;
                return 'Tax Consultant Fee';
                break;

            default:
                abort(404);
        }
    }

    public function getAllDetails($model, $modelId)
    {
        $modelId = decrypt($modelId);
        switch ($model) {
            case User::class:
                $user = User::findOrFail($modelId);
                return $user;
                break;

            case Role::class:
                $role = Role::findOrFail($modelId);
                return 'Role';
                break;

            case TaPaymentConfiguration::class;
                return 'Tax Consultant Fee';
                break;

            default:
                abort(404);
        }
    }

    public function updateControllable($model, $modelId, $status)
    {
        switch ($model) {
            case User::class:
                $user = User::findOrFail($modelId);
                $user->update(['is_approved'=> $status]);
                return $user;
                break;

            case Role::class:
                $role = Role::findOrFail($modelId);
                $role->update(['is_approved'=> $status]);
                return $role;
                break;

            case TaPaymentConfiguration::class;
                return 'Tax Consultant Fee';
                break;

            default:
                abort(404);
        }
    }





}
