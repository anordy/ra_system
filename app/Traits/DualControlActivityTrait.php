<?php

namespace App\Traits;

use App\Models\DualControl;
use App\Models\Role;
use App\Models\TaPaymentConfiguration;
use App\Models\TransactionFee;
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
    public function triggerDualControl($model, $modelId, $action, $action_detail, $old_values = null, $edited_values = null)
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
        $data = $model::findOrFail($modelId);

        if ($action == DualControl::EDIT || $action == DualControl::DELETE) {
            if ($data->is_approved == DualControl::NOT_APPROVED) {
                $this->alert('error', 'The updated module has not been approved already');
                return;
            }
            $data->update(['is_updated' => DualControl::NOT_APPROVED]);
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

            case DualControl::TRANSFER_FEE:
                return 'Transfer Fee';
                break;
            case DualControl::FINANCIAL_YEAR:
                return 'Financial Year';
                break;

            case DualControl::FINANCIAL_MONTH:
                return 'Financial Month';
                break;
            case DualControl::SEVEN_FINANCIAL_MONTH:
                return 'Seven Days Financial Month';
                break;
            case DualControl::PENALTY_RATE:
                return 'Penalty Rate';
                break;
            case DualControl::INTEREST_RATE:
                return 'Interest Rate';
                break;

            case DualControl::ZRBBANKACCOUNT:
                return 'Zrb Bank Account';
                break;
            case DualControl::EXCHANGE_RATE:
                return 'Exchange Rate';
                break;

            case DualControl::COUNTRY:
                return 'Country';
                break;

            case DualControl::DISTRICT:
                return 'District';
                break;
            case DualControl::REGION:
                return 'Region';
                break;
            case DualControl::WARD:
                return 'Ward';
                break;
            case DualControl::TAX_TYPE:
                return 'Tax Type';
                break;

            case DualControl::EDUCATION:
                return 'Education Level';
                break;
            case DualControl::Business_File_Type:
                return 'Business File Type';
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

    public function checkRelation($model, $modelId)
    {
        $data = $model::findOrFail($modelId);
        if (!empty($data)) {
            switch ($model) {
                case DualControl::ROLE:
                    if (count($data->users) > 0) {
                        return false;
                    } else {
                        return true;
                    }
                    break;

                case DualControl::REGION:
                    if (count($data->landLeases) > 0) {
                        return false;
                    } else {
                        return true;
                    }
                    if (!empty($data->taxagent)) {
                        return false;
                    } else {
                        return true;
                    }
                    break;

                default:
                    abort(404);
            }

        }
    }

    public function updateControllable($data, $status)
    {
        $update = $data->controllable_type::findOrFail($data->controllable_type_id);
        if ($data->action == DualControl::ADD) {
            $update->update(['is_approved' => $status]);
        } elseif ($data->action == DualControl::EDIT) {
            $payload = json_decode($data->new_values);
            $payload = (array)$payload;
            if ($status == DualControl::APPROVE) {
                $payload = array_merge($payload, ['is_updated' => DualControl::APPROVE]);
                $update->update($payload);
            } else {
                $update->update(['is_updated' => $status]);
            }
        } elseif ($data->action == DualControl::DELETE) {
            if ($status == DualControl::APPROVE) {
                $update->delete();
            }
        } elseif ($data->action == DualControl::DEACTIVATE || $data->action == DualControl::ACTIVATE) {
            if ($status == DualControl::APPROVE) {
                $payload = (array)json_decode($data->new_values);
                $update->update($payload);
            }
        }
    }
}
