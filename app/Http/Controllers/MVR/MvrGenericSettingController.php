<?php

namespace App\Http\Controllers\MVR;

use App\Http\Controllers\Controller;
use App\Models\CaseDecision;
use App\Models\CaseOutcome;
use App\Models\CaseStage;
use App\Models\CourtLevel;
use App\Models\DlBloodGroup;
use App\Models\DlFee;
use App\Models\DlLicenseClass;
use App\Models\DlLicenseDuration;
use App\Models\GenericSettingModel;
use App\Models\MvrBodyType;
use App\Models\MvrColor;
use App\Models\MvrDeRegistrationReason;
use App\Models\MvrFee;
use App\Models\MvrMake;
use App\Models\MvrModel;
use App\Models\MvrOwnershipTransferReason;
use App\Models\MvrPlateNumberColor;
use App\Models\MvrPlateSize;
use App\Models\MvrRegistrationType;
use App\Models\MvrTransferCategory;
use App\Models\MvrTransferFee;
use App\Models\MvrTransmissionType;
use App\Models\Parameter;
use App\Models\Report;
use Illuminate\Support\Facades\Gate;

class MvrGenericSettingController extends Controller
{

    public function index($model)
    {
        $class = 'App\Models\\' . $model;
        abort_if(!class_exists($class), 404);

        $permission = '';

        if ($class === MvrPlateSize::class) {
            $permission = 'setting-mvr-plate-size-view';
        } else if ($class === MvrMake::class) {
            $permission = 'setting-mvr-make-view';
        } else if ($class === MvrModel::class) {
            $permission = 'setting-mvr-model-view';
        } else if ($class === MvrTransmissionType::class) {
            $permission = 'setting-mvr-transmission-type-view';
        } else if ($class === MvrColor::class) {
            $permission = 'setting-mvr-color-view';
        } else if ($class === MvrBodyType::class) {
            $permission = 'setting-mvr-body-type-view';
        } else if ($class === MvrFee::class) {
            $permission = 'setting-mvr-fee-view';
        } else if ($class === MvrDeRegistrationReason::class) {
            $permission = 'setting-mvr-deregistration-reason-view';
        } else if ($class === MvrOwnershipTransferReason::class) {
            $permission = 'setting-mvr-ownership-transfer-reason-view';
        } else if ($class === MvrTransferCategory::class) {
            $permission = 'setting-mvr-transfer-category-view';
        } else if ($class === MvrTransferFee::class) {
            $permission = 'setting-mvr-transfer-fee-view';
        } else if ($class === DlLicenseClass::class) {
            $permission = 'setting-dl-class-view';
        } else if ($class === DlLicenseDuration::class) {
            $permission = 'setting-dl-duration-view';
        } else if ($class === DlBloodGroup::class) {
            $permission = 'setting-dl-blood-group-view';
        } else if ($class === DlFee::class) {
            $permission = 'setting-dl-fee-view';
        } else if ($class === CaseStage::class) {
            $permission = 'setting-case-stage-view';
        } else if ($class === CaseOutcome::class) {
            $permission = 'setting-case-outcome-view';
        } else if ($class === CaseDecision::class) {
            $permission = 'setting-case-decision-view';
        } else if ($class === CourtLevel::class) {
            $permission = 'setting-court-level-view';
        } else if ($class === MvrPlateNumberColor::class) {
            $permission = 'setting-mvr-color-view';
        } else if ($class === MvrRegistrationType::class) {
            $permission = 'setting-mvr-color-view';
        } else if ($class === Parameter::class) {
            $permission = 'setting-mvr-plate-size-view';
        } else if ($class === Report::class) {
            $permission = 'setting-mvr-plate-size-view';
        }


        if (!Gate::allows($permission)) {
            abort(403);
        }

        if (array_search(GenericSettingModel::class, class_parents($class))) {
            $setting_title = $class::settingTitle();
        } else {
            $setting_title = preg_replace('/^(Mvr|Dl)/', '', $model);
            $setting_title = preg_replace('/([a-z]+)([A-Z])/', '$1 $2', $setting_title);
        }

        return view('settings.mvr-generic', ['model' => $class, 'setting_title' => $setting_title]);
    }
}
