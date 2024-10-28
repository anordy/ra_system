<?php

namespace App\Http\Controllers\NonTaxResident;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\Ntr\NtrBusiness;
use App\Models\Ntr\NtrBusinessDeregistration;
use App\Models\Ntr\NtrBusinessUpdate;
use App\Models\Ntr\Returns\NtrVatReturn;
use Illuminate\Support\Facades\Log;

class NonTaxResidentController extends Controller
{

    public function listBusinesses()
    {
        return view('non-tax-resident.business.index');
    }

    public function showBusiness($id)
    {
        try {
            $id = decrypt($id);
            $business = NtrBusiness::findOrFail($id, ['id', 'ntr_business_category_id', 'other_category', 'ownership_type', 'individual_first_name', 'individual_middle_name', 'individual_last_name', 'individual_position', 'individual_address', 'entity_type', 'name', 'nature_of_business', 'business_address', 'country_id', 'ntr_taxpayer_id', 'street', 'status', 'vrn', 'ztn_number', 'email', 'created_at', 'updated_at', 'deleted_at', 'ztn_location_number',]);
            return view('non-tax-resident.business.show', compact('business'));
        } catch (\Exception $exception) {
            Log::error('NTR-BUSINESS-CONTROLLER-SHOW-BUSINESS', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

    public function listFiledReturns()
    {
        return view('non-tax-resident.returns.index');
    }

    public function listCancelledReturns()
    {
        return view('non-tax-resident.returns.cancelled');
    }

    public function showReturn($id)
    {
        try {
            $id = decrypt($id);
            $return = NtrVatReturn::with([
                'business:id,ntr_business_category_id,other_category,ownership_type,individual_first_name,individual_middle_name,individual_last_name,individual_position,individual_address,entity_type,name,nature_of_business,business_address,country_id,ntr_taxpayer_id,street,status,vrn,ztn_number,email,created_at,ztn_location_number',
                'cancellation:id,return_id,reason,created_at,cancelled_by'
            ])->findOrFail($id, ['id', 'business_id', 'filed_by_type', 'filed_by_id', 'currency', 'tax_type_id', 'financial_year_id', 'financial_month_id', 'edited_count', 'status', 'payment_status', 'return_category', 'principal', 'penalty', 'interest', 'total_amount_due', 'total_amount_due_with_penalties', 'paid_at', 'filing_due_date', 'payment_due_date', 'curr_payment_due_date', 'created_at']);
            return view('non-tax-resident.returns.show', ['return' => $return, 'business' => $return->business]);
        } catch (\Exception $exception) {
            Log::error('NTR-BUSINESS-CONTROLLER-SHOW-BUSINESS', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

    public function listDeregistrations()
    {
        return view('non-tax-resident.de-registrations.index');
    }

    public function showDeregistration($id)
    {
        try {
            $id = decrypt($id);
            $deRegistration = NtrBusinessDeregistration::query()
                ->with(['business:id,name,country_id,ntr_business_category_id,ntr_taxpayer_id,street,status,vrn,ztn_number,ztn_location_number'])
                ->findOrFail($id, ['id', 'ntr_business_id', 'ntr_taxpayer_id', 'marking', 'reason', 'status', 'approved_on', 'rejected_on', 'created_at', 'approved_by', 'rejected_by']);

            return view('non-tax-resident.de-registrations.show', ['business' => $deRegistration->business, 'deRegistration' => $deRegistration]);
        } catch (\Exception $exception) {
            Log::error('NTR-BUSINESS-CONTROLLER-SHOW-DEREGISTRATION', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

    public function listBusinessUpdates()
    {
        return view('non-tax-resident.updates.index');
    }

    public function showBusinessUpdates($id)
    {
        try {
            $id = decrypt($id);
            $updates = NtrBusinessUpdate::query()
                ->with(['business:id,name,country_id,ntr_business_category_id,ntr_taxpayer_id,street,status,vrn,ztn_number,ztn_location_number'])
                ->findOrFail($id, ['id', 'ntr_business_id', 'ntr_taxpayer_id', 'current_business_info', 'current_contacts', 'current_socials', 'current_attachments', 'new_business_info', 'new_contacts', 'new_socials', 'new_attachments', 'created_at']);

            $formattedInfo = [
                'current_business_info' => json_decode($updates->current_business_info, true),
                'current_business_socials' => json_decode($updates->current_socials, true),
                'current_business_attachments' => json_decode($updates->current_attachments, true),
                'current_business_contacts' => json_decode($updates->current_contacts, true),
                'new_business_info' => json_decode($updates->new_business_info, true),
                'new_business_socials' => json_decode($updates->new_socials, true),
                'new_business_attachments' => json_decode($updates->new_attachments, true),
                'new_business_contacts' => json_decode($updates->new_contacts, true),
            ];

            return view('non-tax-resident.updates.show', ['business' => $updates->business, 'updates' => $updates, 'formattedInfo' => $formattedInfo]);
        } catch (\Exception $exception) {
            Log::error('NTR-BUSINESS-CONTROLLER-SHOW-BUSINESS-UPDATES', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }
}