<?php

namespace Database\Seeders;

use App\Models\Returns\StampDuty\StampDutyService;
use Illuminate\Database\Seeder;

class StampDutyItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            [
                'description' => 'Acknowledgement of a debt',
                'rate' => 5000,
                'liable_to' => 'The first person executing'
            ],
            [
                'description' => 'Acknowledgement of receipt - for any money or other property the amount of value exceeds Shs. 50,000/=',
                'rate' => 5000,
                'liable_to' => 'The first person executing'
            ],
            [
                'description' => 'Adoption Deed',
                'rate' => 5000,
                'liable_to' => 'The first person executing'
            ],
            [
                'description' => 'Affidavit including an affirmation or declaration',
                'rate' => 5000,
                'liable_to' => 'Deponee'
            ],
            [
                'description' => 'Agreements',
                'rate_type' => 'percent',
                'rate' => 0.5,
                'liable_to' => 'The first person executing'
            ],
            [
                'description' => 'Agreements relating to deposit of title - deeds, pawn pledge-of the total value',
                'rate_type' => 'percent',
                'rate' => 0.05,
                'liable_to' => 'The first person executing'
            ],
            [
                'description' => 'Appointment in execution of a power, whether of trustees or of property',
                'rate' => 5000,
                'liable_to' => 'Appointee — the person making or execute the appointment'
            ],
            [
                'description' => 'Articles of Association of a Company',
                'rate' => 20000,
                'liable_to' => 'The A pointer'
            ],
            [
                'description' => 'Assent to bequest whether under hand or seal',
                'rate' => 5000,
                'liable_to' => 'The testator'
            ],
            [
                'description' => 'Award- by arbitrator or umpire',
                'rate' => 5000,
                'liable_to' => 'The person making or executing the award'
            ],
            [
                'description' => 'Any other instrument not specifically mentioned',
                'rate' => 5000,
                'liable_to' => 'Person issuing'
            ],
            [
                'description' => 'Bill of exchange not being a bond, bank note or currency note',
                'rate' => 5000,
                'liable_to' => 'The drawee'
            ],
            [
                'description' => 'Bill of exchange or promissory note',
                'rate' => 10000,
                'liable_to' => 'The drawer or acceptor'
            ],
            [
                'description' => 'Bill of Lading (including a thorough bill of lading, airway bill and telex release)',
                'rate' => 5000,
                'liable_to' => 'The person by whom the goods are consigned. Master/Agent'
            ],
            [
                'description' => 'Bill of Sale-of the value',
                'rate_type' => 'percent',
                'rate' => 0.5,
                'max' => 100000,
                'liable_to' => 'The assignor'
            ],
            [
                'description' => 'Bond (not being a debenture)',
                'rate' => 5000,
                'liable_to' => 'The obligor or other person giving the security'
            ],
            [
                'description' => 'Cancellation — of instrument',
                'rate' => 5000,
                'liable_to' => 'The person who was responsible for stamping the original instrument'
            ],
            [
                'description' => 'Charter Party (instrument for charter hire or vessel or part	of it',
                'rate_type' => 'percent',
                'rate' => 0.5,
                'liable_to' => 'Charterer'
            ],
            [
                'description' => 'Chegre, forever such instrument',
                'rate' => 500,
                'liable_to' => 'The Transferee or Receiver'
            ],
            [
                'description' => 'Capital Duty on nominal share capital or any increase of it of any company incorporated in Zanzibar with limited liability—of the total value.',
                'rate_type' => 'percent',
                'rate' => 0.5,
                'liable_to' => 'The bearer'
            ],
            [
                'description' => 'Copy of Extract',
                'rate' => 5000,
                'liable_to' => 'Person for or on whose behalf the copy or extract is made'
            ],
            [
                'description' => 'Counterpart or Duplicate of an instrument chargeable with duty and in respect of which the property duty has been paid',
                'rate' => 5000,
                'liable_to' => 'The person chargeable in the original instrument or covenantee'
            ],
            [
                'description' => 'Counterpart of a Lease',
                'rate' => 5000,
                'liable_to' => 'The lessor'
            ],
            [
                'description' => 'Counterpart of a Lease',
                'rate_type' => 'percent',
                'rate' => 0.5,
                'liable_to' => 'The creditor'
            ],
            [
                'description' => 'Contract note relating for any Stock',
                'rate_type' => 'percent',
                'rate' => 0.1,
                'liable_to' => 'The brokers or agent or where there is no broker or agent the principal delivering the note'
            ],
            [
                'description' => 'Conveyance (not being transfer) of the total value',
                'rate_type' => 'percent',
                'rate' => 1,
                'liable_to' => 'The purchaser or transferee'
            ],
            [
                'description' => 'Conveyance, including conveyance of mortgaged property',
                'rate_type' => 'percent',
                'rate' => 1,
                'liable_to' => 'The transferee/mortgagee'
            ],
            [
                'description' => 'Customs Bonds — of the total value',
                'rate_type' => 'percent',
                'rate' => 0.05,
                'liable_to' => 'The first person executing'
            ],
            [
                'description' => 'Deed',
                'rate' => 5000,
                'liable_to' => 'The parties to the deed or any one of them'
            ],
            [
                'description' => 'Debenture—whether a mortgage debenture or not, being of a marketable security-of the total value',
                'rate_type' => 'percent',
                'rate' => 0.05,
                'max' => 100000,
                'liable_to' => 'The person issuing'
            ],
            [
                'description' => 'Debenture or stock',
                'rate_type' => 'percent',
                'rate' => 1,
                'liable_to' => 'The person issuing'
            ],
            [
                'description' => 'Dissolution of Partnership',
                'rate' => 10000,
                'liable_to' => 'The person issuing'
            ],
            [
                'description' => 'Divorce — (any instrument by which any person effects the dissolution of marriage)',
                'rate' => 5000,
                'liable_to' => 'Deliverer'
            ],
            [
                'description' => 'Equitable Mortgage — of the total value',
                'rate_type' => 'percent',
                'rate' => 0.5,
                'liable_to' => 'Mortgagor'
            ],
            [
                'description' => 'Exchange of property — of the total value',
                'rate_type' => 'percent',
                'rate' => 0.5,
                'liable_to' => 'All person exchanges'
            ],
            [
                'description' => 'Extract',
                'rate' => 5000,
                'liable_to' => 'The person issuing'
            ],
            [
                'description' => 'Further charge—any instrument imposing a further charge on mortgaged property — of the total value',
                'rate_type' => 'percent',
                'rate' => 0.5,
                'liable_to' => 'The grantee'
            ],
            [
                'description' => 'Gift- instrument of not being imposing a further charge on mortgaged property-of the total value',
                'rate_type' => 'percent',
                'rate' => 1,
                'liable_to' => 'The person executing'
            ],
            [
                'description' => 'Hire Purchase Agreement — of the total value.',
                'rate_type' => 'percent',
                'rate' => 1,
                'liable_to' => 'Purchaser'
            ],
            [
                'description' => 'Encumbrances deed',
                'rate' => 10000,
                'liable_to' => 'Incumbrancer'
            ],
            [
                'description' => 'Indemnity Bond',
                'rate_type' => 'percent',
                'rate' => 1,
                'liable_to' => 'The grantor'
            ],
            [
                'description' => 'Insurance performance bond',
                'rate' => 20000,
                'liable_to' => 'Insurer'
            ],
            [
                'description' => 'Life Insurance',
                'rate_type' => 'percent',
                'rate' => 0.5,
                'liable_to' => 'The Company or person issuing the policy or policy holder'
            ],
            [
                'description' => 'Land Lease—of the total value',
                'rate_type' => 'percent',
                'rate' => 1,
                'liable_to' => 'Lessee or tenant'
            ],
            [
                'description' => 'Another Lease',
                'rate_type' => 'percent',
                'rate' => 0.5,
                'liable_to' => 'The lessor'
            ],
            [
                'description' => 'Letter of Credit—an instrument by which one-person authorizes another to give credit to the person in whose favor it is drawn.',
                'rate' => 5000,
                'liable_to' => 'A person in favor of letter of credit'
            ],
            [
                'description' => 'Letter of Licence—any agreement between a debtor and his creditors that the latter shall for a specified time, suspend their claims and allow the debtor to carry on business at his own discretion',
                'rate' => 5000,
                'liable_to' => 'The Debtor'
            ],
            [
                'description' => 'Loan',
                'rate_type' => 'percent',
                'rate' => 0.5,
                'max' => 100000,
                'liable_to' => 'The person requiring'
            ],
            [
                'description' => 'Marine Insurance',
                'rate_type' => 'percent',
                'rate' => 0.5,
                'liable_to' => 'Company or person issuing the policy or policy holder'
            ],
            [
                'description' => 'Memorandum of Association of a Company',
                'rate' => 20000,
                'liable_to' => 'The Company'
            ],
            [
                'description' => 'A. Mortgage  Deed -  of  the  total  value  a mortgagor who gives a power of attorney to collect rents or a lease of the property mortgaged is deemed to give possession within the meaning of this item.',
                'rate_type' => 'percent',
                'rate' => 0.1,
                'liable_to' => 'The person issuing'
            ],
            [
                'description' => 'B. Where a collateral   or   auxiliary   or additional or substituted security is given by way of further assurance where the principal or primary security is duly stamped.',
                'rate' => 5000,
                'liable_to' => 'The person issuing'
            ],
            [
                'description' => 'Notarial Act—made or signed by a Notary Public in the Execution of the duties of his office, or by any other person lawfully acting as a Notary Public',
                'rate' => 5000,
                'liable_to' => 'The person requiring the act'
            ],
            [
                'description' => 'Partition',
                'rate_type' => 'percent',
                'rate' => 0.5,
                'liable_to' => 'The parties to it in proportion to their respective shares in the whole property partitioned'
            ],
            [
                'description' => 'Partnership Deed',
                'rate' => 20000,
                'liable_to' => 'All persons executing'
            ],
            [
                'description' => 'Policy of Insurance',
                'rate' => 20000,
                'liable_to' => 'The Company or person issuing the policy or policy holder'
            ],
            [
                'description' => 'Protest of bill or note—any declaration in writing made by a Notary Public, attesting the dishonor of a bill of exchange or promissory note',
                'rate' => 5000,
                'liable_to' => 'The person executing'
            ],
            [
                'description' => 'Power of Attorney',
                'rate' => 5000,
                'liable_to' => 'The person executing'
            ],
            [
                // TODO: Validate with authorities
                'description' => 'Receipt or Bill of Sale composition agreement under this Act',
                'rate_type' => 'percent',
                'rate' => 3,
                'liable_to' => 'The person issuing a receipt'
            ],
            [
                'description' => 'Reconveyance of mortgaged property—of the total value.',
                'rate_type' => 'percent',
                'rate' => 0.01,
                'liable_to' => 'The grantee'
            ],
            [
                'description' => 'Release—Any instrument not being such a release as is provided by which a person renounces a claim upon another person or against any specified property',
                'rate' => 5000,
                'liable_to' => 'The person the release issued to him'
            ],
            [
                'description' => 'Settlement; instrument of — (including a deed of dower or revocation.',
                'rate_type' => 'percent',
                'rate' => 0.5,
                'liable_to' => 'The person making the settlement'
            ],
            [
                'description' => 'Shipping Order—for or relating to the conveyance of goods on board any vessel.',
                'rate' => 5000,
                'liable_to' => 'The person issuing the order'
            ],
            [
                'description' => 'Solemn or statutory declaration',
                'rate' => 5000,
                'liable_to' => 'The person who make declaration'
            ],
            [
                'description' => 'Surrender of Lease',
                'rate' => 5000,
                'liable_to' => 'The person surrender the lease'
            ],
            [
                'description' => 'Transfer of the total value',
                'rate_type' => 'percent',
                'rate' => 1,
                'liable_to' => 'Transferee'
            ],
            [
                'description' => 'Transfer of shares in an incorporated company listed on the stock exchange, arising from the trading of those shares on the stock exchange.',
                'rate_type' => 'percent',
                'rate' => 0.5,
                'liable_to' => 'Transferee'
            ],
            [
                'description' => 'Trust—concerning any property made by any writing not being a will',
                'rate' => 5000,
                'liable_to' => 'Trustee'
            ],
            [
                'description' => 'Transfer of Shares or Share warrants—to bearer issued under the Companies Act—of the total value',
                'rate_type' => 'percent',
                'rate' => 1,
                'liable_to' => 'The bearer'
            ],
            [
                'description' => 'Transfer of Stock',
                'rate' => 5000,
                'liable_to' => 'Purchaser or transferee'
            ],
            [
                'description' => 'Acknowledgement',
                'rate_type' => 'percent',
                'rate' => 0.1,
                'liable_to' => 'The first person executing'
            ],
        ];
        foreach ($services as $service) {
            StampDutyService::create($service);
        }
    }
}
