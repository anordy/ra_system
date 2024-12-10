<?php

namespace App\Http\Livewire\Business\Search;

use App\Enum\BusinessQueryIdentifierType;
use App\Enum\BusinessQueryType;
use App\Enum\CustomMessage;
use App\Models\Business;
use App\Models\BusinessTaxType;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Validation\Rule;

class SearchBusiness extends Component
{
    use CustomAlert;

    public $queryType, $identifierData, $identifierType, $businesses = [], $businessInfo;
    private $limitQueryResults = 3;

    protected function rules()
    {
        return [
            'queryType' => ['required', Rule::in(BusinessQueryType::getConstants())],
            'identifierType' => ['required', Rule::in(BusinessQueryIdentifierType::getConstants())],
            'identifierData' => ['required', 'alpha_gen'],
        ];
    }


    public function mount()
    {

    }

    public function viewBusiness($id) {
        try {
            $this->businessInfo = Business::find(decrypt($id), [
                'id', 'status', 'name', 'business_category_id', 'taxpayer_id', 'bpra_no',
                'business_type', 'business_activities_type_id', 'currency_id', 'trading_name',
                'tin', 'previous_zno', 'reg_no', 'owner_designation', 'mobile', 'alt_mobile', 'email',
                'place_of_business', 'goods_and_services_types', 'goods_and_services_example', 'responsible_person_id',
                'is_own_consultant', 'is_business_lto', 'reg_date', 'approved_on', 'isiic_i', 'isiic_ii',
                'isiic_iii', 'isiic_iv', 'ztn_number', 'no_of_branches', 'bpra_verification_status', 'vrn',
                'is_owner', 'taxpayer_name', 'correction_part'
            ]);

            if ($this->queryType === BusinessQueryType::TAX_TYPE) {
                $taxTypes = BusinessTaxType::query()
                    ->with('subvat')
                    ->where('business_id', $this->businessInfo->id)
                    ->select('id', 'tax_type_id', 'currency', 'sub_vat_id')
                    ->get();

                $this->businessInfo->taxes = $taxTypes;
            }
        } catch (\Exception $exception) {
            Log::error('BUSINESS-SEARCH-BUSINESS-VIEW-BUSINESS', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    public function search()
    {
        $this->validate();

        try {
            $this->businessInfo = null;
            $this->businesses = Business::query()
                ->select('id', 'name', 'email', 'ztn_number', 'tin', 'mobile', 'place_of_business');

            if ($this->identifierType === BusinessQueryIdentifierType::BUSINESS_NAME) {
                $this->businesses = $this->businesses->WhereRaw(\DB::raw("LOWER(name) like '%' || LOWER('$this->identifierData') || '%'"))
                    ->limit($this->limitQueryResults)
                    ->get();
            } else if ($this->identifierType === BusinessQueryIdentifierType::ZTN_NUMBER) {
                $this->businesses = $this->businesses->WhereRaw(\DB::raw("LOWER(ztn_number) like '%' || LOWER('$this->identifierData') || '%'"))
                    ->limit($this->limitQueryResults)
                    ->get();
            } else {
                $this->customAlert('warning', 'Invalid Identifier Type Provided');
                return;
            }

        } catch (\Exception $exception) {
            Log::error('BUSINESS-SEARCH-BUSINESS-SEARCH', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    protected $messages = [
        'identifierData.required' => 'Invalid search data',
        'queryType.required' => 'Invalid query type',
        'identifierData.alpha_gen' => 'Query Data must be valid alphabets and numbers only',
    ];

    public function clear() {
        $this->reset('identifierType', 'identifierData', 'businessInfo', 'queryType');
    }


    public function render()
    {
        return view('livewire.business.search.search');
    }
}
