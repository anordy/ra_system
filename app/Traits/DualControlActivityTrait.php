<?php

namespace App\Traits;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\DualControl;
use App\Models\DualControlHistory;
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

        $result = DualControl::updateOrCreate($payload);

        $this->updateHistory($model, $modelId, $result->id,  $action, null );
        $data = $model::findOrFail($modelId);
        if ($action == DualControl::EDIT || $action == DualControl::DELETE) {
            $data->update(['is_updated' => DualControl::NOT_APPROVED]);
            if ($model == DualControl::USER) {
                $message = 'We are writing to inform you that some of your ZRA staff personal information has been requested to be changed in our records. If you did not request these changes or if you have any concerns, please contact us immediately.';
                $this->sendEmailToUser($data, $message);
            }
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

            case DualControl::CONSULTANT_DURATION:
                return 'Tax Consultant Duration';
                break;
            case DualControl::SYSTEM_SETTING_CONFIG:
                return 'System Setting Configuration';
                break;
            case DualControl::SYSTEM_SETTING_CATEGORY:
                return 'System Setting Category Configuration';
                break;

            case DualControl::TRANSACTION_FEE:
                return 'Transaction Fee';
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
                return 'ZRA Bank Account';
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
            case DualControl::STREET:
                return 'Street';
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
            case DualControl::API_USER:
                return 'API User';
                break;
            case DualControl::VAT_TAX_TYPE:
                return 'VAT Tax Type';
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
            $update->is_approved = $status;
            $update->save();
        } elseif ($data->action == DualControl::EDIT) {
            $payload = json_decode($data->new_values);
            $payload = (array) $payload;
            if ($status == DualControl::APPROVE) {
                
                $payload = array_merge($payload, ['is_updated' => DualControl::APPROVE]);
                $update->update($payload);
                if ($data->controllable_type == DUalControl::USER) {
                    $message = 'We are writing to inform you that some of your ZRA staff personal information has been changed in our records. If you did not request these changes or if you have any concerns, please contact us immediately.';
                    $this->sendEmailToUser($update, $message);
                }

            } else {
                $update->update(['is_updated' => $status]);
            }
        } elseif ($data->action == DualControl::DELETE) {
            if ($status == DualControl::APPROVE) {
                $update->delete();
            }
        } elseif ($data->action == DualControl::DEACTIVATE || $data->action == DualControl::ACTIVATE) {
            if ($status == DualControl::APPROVE) {
                $payload = (array) json_decode($data->new_values);
                $update->update($payload);
            }
        }
    }

    public function sendEmailToUser($data, $message)
    {
        $smsPayload = [
            'phone' => $data->phone,
            'message' => $message,
        ];

        $emailPayload = [
            'email' => $data->email,
            'userName' => $data->first_name,
            'message' => $message,
        ];

        event(new SendSms('dual-control-update-user-info-notification', $smsPayload));
        event(new SendMail('dual-control-update-user-info-notification', $emailPayload));
    }

    public function updateHistory(
        $controllable_type,
        $controllable_id,
        $dual_control_id,
        $action,
        $comment

    )
    {
        $payload = [
            'controllable_type' => $controllable_type,
            'controllable_id' => $controllable_id,
            'dual_control_id' => $dual_control_id,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'action' => $action,
            'comment' => $comment,
        ];
        DualControlHistory::create($payload);
    }
}
