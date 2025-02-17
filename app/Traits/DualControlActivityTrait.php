<?php

namespace App\Traits;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Jobs\Workflow\UserUpdateActors;
use App\Models\DualControl;
use App\Models\DualControlHistory;
use App\Models\VfmsWard;
use App\Models\Ward;
use App\Traits\Vfms\VfmsLocationTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait DualControlActivityTrait
{
    use VerificationTrait, VfmsLocationTrait;

    public function triggerDualControl($model, $modelId, $action, $action_detail, $old_values = null, $edited_values = null)
    {
        try {
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

            $this->updateHistory($model, $modelId, $result->id, $action, null);
            $data = $model::findOrFail($modelId);
            if ($action == DualControl::EDIT || $action == DualControl::DELETE) {
                $data->update(['is_updated' => DualControl::NOT_APPROVED]);
                if ($model == DualControl::USER) {
                    $message = 'We are writing to inform you that some of your ZRA staff personal information has been requested to be changed in our records. If you did not request these changes or if you have any concerns, please contact us immediately.';
                    $this->sendEmailToUser($data, $message);
                }
            }
        } catch (\Exception $exception) {
            Log::error('TRAITS-DUAL-CONTROL-ACTIVITY-TRAIT-TRIGGER-DUAL-CONTROL', [$exception]);
            throw $exception;
        }
    }

    public function getModule($model)
    {
        try {
            switch ($model) {
                case DualControl::USER:
                    return 'User';
                case DualControl::ROLE:
                    return 'Role';
                case DualControl::CONSULTANT_DURATION:
                    return 'Tax Consultant Duration';
                case DualControl::SYSTEM_SETTING_CONFIG:
                    return 'System Setting Configuration';
                case DualControl::SYSTEM_SETTING_CATEGORY:
                    return 'System Setting Category Configuration';
                case DualControl::TRANSACTION_FEE:
                    return 'Transaction Fee';
                case DualControl::REORDER_FEE:
                        return 'Reorder Fee';
                case DualControl::FINANCIAL_YEAR:
                    return 'Financial Year';
                case DualControl::FINANCIAL_MONTH:
                    return 'Financial Month';
                case DualControl::SEVEN_FINANCIAL_MONTH:
                    return 'Seven Days Financial Month';
                case DualControl::PENALTY_RATE:
                    return 'Penalty Rate';
                case DualControl::INTEREST_RATE:
                    return 'Interest Rate';
                case DualControl::ZRBBANKACCOUNT:
                    return 'ZRA Bank Account';
                case DualControl::EXCHANGE_RATE:
                    return 'Exchange Rate';
                case DualControl::COUNTRY:
                    return 'Country';
                case DualControl::DISTRICT:
                    return 'District';
                case DualControl::REGION:
                    return 'Region';
                case DualControl::WARD:
                    return 'Ward';
                case DualControl::STREET:
                    return 'Street';
                case DualControl::TAX_TYPE:
                    return 'Tax Type';
                case DualControl::EDUCATION:
                    return 'Education Level';
                case DualControl::Business_File_Type:
                    return 'Business File Type';
                case DualControl::API_USER:
                    return 'API User';
                case DualControl::VAT_TAX_TYPE:
                    return 'VAT Tax Type';
                case DualControl::CERTIFICATE_SIGNATURE:
                    return 'Certificate Signature';
                case DualControl::VIABLE_TAX_TYPE_CHANGE:
                    return 'Viable Tax Type Change';
                default:
                    return 'Missing Dual Control Type';
            }
        } catch (\Exception $exception) {
            Log::error('TRAITS-DUAL-CONTROL-ACTIVITY-TRAIT-GET-MODEL', [$exception]);
            throw $exception;
        }
    }

    public function getAllDetails($model, $modelId)
    {
        try {
            $modelId = decrypt($modelId);
            return $model::findOrFail($modelId);
        } catch (\Exception $exception) {
            Log::error('TRAITS-DUAL-CONTROL-ACTIVITY-TRAIT-GET-ALL-DETAILS', [$exception]);
            throw $exception;
        }
    }

    public function checkRelation($model, $modelId)
    {
        try {
            $data = $model::findOrFail($modelId);
            if (!empty($data)) {
                switch ($model) {
                    case DualControl::ROLE:
                        if (count($data->users) > 0) {
                            return false;
                        } else {
                            return true;
                        }

                    case DualControl::REGION:
                        if (count($data->landLeases) > 0) {
                            return false;
                        } else {
                            return true;
                        }

                    default:
                        abort(404);
                }
            }
            abort(404);

        } catch (\Exception $exception) {
            Log::error('TRAITS-DUAL-CONTROL-ACTIVITY-TRAIT-CHECK-RELATION', [$exception]);
            throw $exception;
        }
    }

    public function updateControllable($data, $status)
    {
        try {
            $update = $data->controllable_type::findOrFail($data->controllable_type_id);

            if ($data->action == DualControl::ADD) {
                $update->is_approved = $status;
                $update->save();

                if ($data->controllable_type == Ward::class) {

                    $payload = $update->vfmsLocalityData();
                    $response = $this->addWardToVfms($payload);
                    if ($response['data']) {
                        $data = json_decode($response['data'], true);
                        if (array_key_exists('statusCode', $data)) {

                            //Send email to inform admin for proper update on both end
                            $message = "This alert email concerning creating CRDB new Ward to Vfms Locality(Wards) Records. As " . $payload['locality_name'] . " ward already exists on Vfms records.";
                            $this->sendnotificationToAdmin($message);

                            $this->customAlert('warning', 'Ward already exists on Vfms records, please kindly report to administrator.');
                            Log::channel('vfms')->error($data['statusMessage']);
                            Log::channel('vfms')->info($response);
                        } else {
                            VfmsWard::create([
                                'ward_id' => $update->id,
                                'locality_id' => $data['locality_id'],
                                'locality_name' => $update->name,
                            ]);
                        }
                    } else {
                        //Send email to inform admin for proper update on both end
                        $message = "This alert email concerning creating CRDB new Ward to Vfms Locality(Wards) Records. Inspect the logs as no response after new ward created on VFMS side.";
                        $this->sendnotificationToAdmin($message);

                        Log::channel('vfms')->error('No response data after new ward entry to vfms, please kindly report to administrator.');
                        Log::channel('vfms')->info($response);
                        $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
                    }
                }

            } elseif ($data->action == DualControl::EDIT) {
                $payload = json_decode($data->new_values);
                $payload = (array)$payload;
                if ($status == DualControl::APPROVE) {

                    $payload = array_merge($payload, ['is_updated' => DualControl::APPROVE]);
                    $update->update($payload);
                    if ($data->controllable_type == DualControl::USER) {
                        $this->sign($update);
                        $message = 'We are writing to inform you that some of your ZRA staff personal information has been changed in our records. If you did not request these changes or if you have any concerns, please contact us immediately.';
                        $this->sendEmailToUser($update, $message);
                    }

                    if (str_contains($data->action_detail, 'editing user role')) {
                        dispatch(new UserUpdateActors($data->controllable_type_id));
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
                    $payload = (array)json_decode($data->new_values);
                    $update->update($payload);
                }
            }

        } catch (\Exception $exception) {
            Log::error('TRAITS-DUAL-CONTROL-ACTIVITY-TRAIT-UPDATE-CONTROLLABLE', [$exception]);
            throw $exception;
        }
    }

    public function sendEmailToUser($data, $message)
    {
        try {
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

        } catch (\Exception $exception) {
            Log::error('TRAITS-DUAL-CONTROL-ACTIVITY-TRAIT-SEND-EMAIL-TO-USER', [$exception]);
            throw $exception;
        }
    }

    public function updateHistory(
        $controllable_type,
        $controllable_id,
        $dual_control_id,
        $action,
        $comment
    )
    {
        try {
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

        } catch (\Exception $exception) {
            Log::error('TRAITS-DUAL-CONTROL-ACTIVITY-TRAIT-UPDATE-HISTORY', [$exception]);
            throw $exception;
        }
    }
}
