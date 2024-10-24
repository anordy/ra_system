<?php

namespace App\Http\Controllers\NonTaxResident;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\Ntr\NtrBusiness;
use App\Models\Ntr\NtrBusinessUpdate;
use Illuminate\Support\Facades\Log;

class NonTaxResidentController extends Controller
{

    public function listBusinesses() {
        return view('non-tax-resident.business.index');
    }

    public function showBusiness($id) {
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

    public function listReturns() {
        return view('non-tax-resident.returns.index');
    }

    public function showReturn() {
        return view('non-tax-resident.returns.show');
    }

    public function listDeregistrations() {
        return view('non-tax-resident.deregistrations.show');
    }

    public function showDeregistration() {
        return view('non-tax-resident.deregistrations.show');
    }

    public function listBusinessUpdates() {
        return view('non-tax-resident.updates.index');
    }

    public function showBusinessUpdates($id) {
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