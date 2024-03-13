<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessDirector;
use App\Models\BusinessShare;
use App\Models\BusinessShareholder;
use Illuminate\Support\Facades\Gate;

class RegistrationController extends Controller
{
    public function index()
    {
        if (!Gate::allows('business-registration-view')) {
            abort(403);
        }
        return View('business.registrations.index');
    }

    public function show($businessId)
    {
        if (!Gate::allows('business-registration-view')) {
            abort(403);
        }
        $businessInfo = $this->getBusinessInfo($businessId);
        $business = $businessInfo['business'];
        $directors = $businessInfo['directors'];
        $shareholders = $businessInfo['shareholders'];
        $shares = $businessInfo['shares'];
        return view('business.registrations.show', compact('business', 'directors', 'shareholders', 'shares'));
    }

    public function approval($businessId)
    {
        if (!Gate::allows('business-registration-view')) {
            abort(403);
        }
        $businessInfo = $this->getBusinessInfo($businessId);
        $business = $businessInfo['business'];
        $directors = $businessInfo['directors'];
        $shareholders = $businessInfo['shareholders'];
        $shares = $businessInfo['shares'];
        return view('business.registrations.approval', compact('business', 'directors', 'shareholders', 'shares'));
    }

    public function correction($businessId)
    {
        if (!Gate::allows('business-registration-view')) {
            abort(403);
        }
        $businessInfo = $this->getBusinessInfo($businessId);
        $business = $businessInfo['business'];
        $directors = $businessInfo['directors'];
        $shareholders = $businessInfo['shareholders'];
        $shares = $businessInfo['shares'];
        return view('business.registrations.correction', compact('business', 'directors', 'shareholders', 'shares'));
    }

    public function approval_progress($businessId)
    {
        if (!Gate::allows('business-registration-view')) {
            abort(403);
        }
        $businessInfo = $this->getBusinessInfo($businessId);
        $business = $businessInfo['business'];
        $directors = $businessInfo['directors'];
        $shareholders = $businessInfo['shareholders'];
        $shares = $businessInfo['shares'];
        return view('business.registrations.approval_progress', compact('business', 'directors', 'shareholders', 'shares'));
    }

    private function getBusinessInfo($businessId)
    {
        $business = Business::findOrFail(decrypt($businessId), [
            'id', 'status', 'name', 'business_category_id', 'taxpayer_id', 'bpra_no',
            'business_type', 'business_activities_type_id', 'currency_id', 'trading_name',
            'tin', 'previous_zno', 'reg_no', 'owner_designation', 'mobile', 'alt_mobile', 'email',
            'place_of_business', 'goods_and_services_types', 'goods_and_services_example', 'responsible_person_id',
            'is_own_consultant', 'is_business_lto', 'reg_date', 'approved_on', 'isiic_i', 'isiic_ii',
            'isiic_iii', 'isiic_iv', 'ztn_number', 'no_of_branches', 'bpra_verification_status', 'vrn',
            'is_owner', 'taxpayer_name', 'correction_part'
        ]);

        $directors = BusinessDirector::select([
            'id', 'business_id', 'first_name', 'middle_name', 'last_name', 'gender', 'mob_phone', 'email',
            'city_name', 'first_line'
        ])->where('business_id', $business->id)->get() ?? [];

        $shareholders = BusinessShareholder::select([
            'id', 'business_id', 'entity_name', 'first_name', 'middle_name', 'last_name', 'gender', 'mob_phone',
            'email', 'city_name', 'first_line'
        ])->where('business_id', $business->id)->get() ?? [];

        $shares = BusinessShare::select(['id', 'business_id', 'share_holder_id', 'shareholder_name', 'share_class',
            'number_of_shares', 'currency', 'number_of_shares_taken', 'number_of_shares_paid'
        ])->where('business_id', $business->id)->get() ?? [];

        return [
            'business' => $business,
            'directors' => $directors,
            'shareholders' => $shareholders,
            'shares' => $shares
        ];
    }
}
