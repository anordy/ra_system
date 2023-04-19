<?php

namespace App\Traits;

use App\Enum\ReturnApplicationStatus;
use App\Enum\TaxClaimStatus;
use App\Models\Claims\TaxClaim;
use App\Models\Claims\TaxCredit;
use App\Models\Claims\TaxCreditItem;
use Illuminate\Support\Facades\Auth;

trait TaxClaimsTrait {

    use WorkflowProcesssingTrait;

    private $allowedCurrencies = [
        'USD',
        'TZS',
        'EUR'
    ];

    /**
     * @param int $amount
     * @param string $currency TZS|USD|EUR
     * @param $oldReturn
     * @param null $newReturn
     * @return void
     * @throws \Exception
     */
    public function triggerClaim(int $amount, string $currency, $oldReturn, $newReturn = null){
        if ($oldReturn == null || $amount == null || $currency == null){
            throw new \Exception('Incorrect format.');
        }

        if (!in_array($currency, $this->allowedCurrencies)){
            throw new \Exception('Incorrect format');
        }

        $claim = TaxClaim::create([
            'old_return_id' => $oldReturn->id,
            'old_return_type' => get_class($oldReturn),
            'new_return_id' => $newReturn ? $newReturn->id : null,
            'new_return_type' => $newReturn ? get_class($newReturn) : null,
            'business_id' => $oldReturn->business_id,
            'location_id' => $oldReturn->business_location_id,
            'tax_type_id' => $oldReturn->tax_type_id,
            'created_by_type' => get_class(Auth::user()),
            'created_by_id' => Auth::id(),
            'financial_month_id' => $oldReturn->financial_month_id,
            'amount' => $amount,
            'currency' => $currency
        ]);

        $oldReturn->claim_status = TaxClaimStatus::CLAIM;
        $oldReturn->save();
        $this->registerWorkflow(get_class($claim), $claim->id);
        $this->doTransition('start', ['approved']);
        $claim->status = TaxClaimStatus::PENDING;
        $claim->save();
        return $claim;
    }

    /**
     * Get total amount of CBF that can be used.
     * @param $creditable int|TaxCredit
     * @param $currency
     * @return array
     * @throws \Exception
     */
    public function getUsableCredit($creditable, $currency): array
    {
        if (is_numeric($creditable)) {
            $credit = TaxCredit::query()->find($creditable);
        } else if ($creditable instanceof TaxCredit) {
            $credit = $creditable;
        } else {
            throw new \Exception('Invalid tax credit supplied.');
        }

        if (!$credit->hasCredit($currency)){
            throw new \Exception('No usable credit found.');
        }

        if ($credit->payment_method == 'full'){
            return [
                'amount' => $credit->amount - $credit->spentCredit,
                'currency' => $credit->currency
            ];
        }

        if ($credit->payment_method == 'installment'){
            $installable = $credit->amount / $credit->installments_count;
            $available = $credit->amount - $credit->spentCredit;

            if ($available > $installable){
                return [
                    'amount' => $installable,
                    'currency' => $credit->currency
                ];
            }

            return [
                'amount' => $available,
                'currency' => $credit->currency
            ];
        }

        throw new \Exception('No usable credit found');
    }


    /**
     * Use available CBF
     * @param $creditable
     * @param $return - return instance
     * @param $maxAmount
     * @param $currency
     * @return void
     * @throws \Exception
     */
    public function useCredit($creditable, $return, $maxAmount, $currency){
        if (is_numeric($creditable)) {
            $credit = TaxCredit::query()->find($creditable);
        } else if ($creditable instanceof TaxCredit) {
            $credit = $creditable;
        } else {
            throw new \Exception('Invalid tax credit supplied.');
        }

        $usable = $this->getUsableCredit($credit, $currency);

        $amount = $usable['amount'];

        if ($usable['amount'] > $maxAmount){
            $amount = $maxAmount;
        }

        // Spend the difference
        $item = TaxCreditItem::create([
            'credit_id' => $credit->id,
            'return_id' => $return->id,
            'return_type' => get_class($return),
            'amount' => $amount,
            'currency' => $credit->currency
        ]);

        return $item;
    }
}