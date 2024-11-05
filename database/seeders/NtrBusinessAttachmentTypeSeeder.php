<?php

namespace Database\Seeders;

use App\Enum\NonTaxResident\NtrBusinessType;
use App\Models\Ntr\NtrBusinessAttachmentType;
use Illuminate\Database\Seeder;

class NtrBusinessAttachmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attachments = [
            [
                'name' => 'Business Certificate',
                'is_for_entity' => false,
                'is_required' => true,
                'business_type' => NtrBusinessType::NON_RESIDENT,
                'description' => 'Official document certifying the establishment and legality of the business.'
            ],
            [
                'name' => 'Certificate of Incorporation/Business License',
                'is_for_entity' => true,
                'is_required' => true,
                'business_type' => NtrBusinessType::NON_RESIDENT,
                'description' => 'Legal certification proving the business is registered and authorized to operate.'
            ],
            [
                'name' => 'Proof of Business Address',
                'is_for_entity' => false,
                'is_required' => false,
                'business_type' => NtrBusinessType::NON_RESIDENT,
                'description' => 'Document verifying the physical location of the business premises.'
            ],
            [
                'name' => 'TIN',
                'is_for_entity' => false,
                'is_required' => false,
                'business_type' => NtrBusinessType::NON_RESIDENT,
                'description' => 'Tax Identification Number (TIN) required for tax compliance and identification.'
            ],
            [
                'name' => 'Power of Attorney',
                'is_for_entity' => false,
                'is_required' => false,
                'business_type' => NtrBusinessType::NON_RESIDENT,
                'description' => 'Authorization document allowing a representative to act on behalf of the business.'
            ],
            [
                'name' => 'Bank Account Information',
                'is_for_entity' => false,
                'is_required' => false,
                'business_type' => NtrBusinessType::NON_RESIDENT,
                'description' => 'Bank statement or account information showing business financial details.'
            ],
            [
                'name' => 'Business Incorporation Certificate',
                'is_for_entity' => false,
                'is_required' => true,
                'business_type' => NtrBusinessType::ECOMMERCE,
                'description' => 'Certificate issued by the regulatory authority confirming business registration.'
            ],
            [
                'name' => 'Website Ownership Proof',
                'is_for_entity' => false,
                'is_required' => false,
                'business_type' => NtrBusinessType::ECOMMERCE,
                'description' => 'Evidence demonstrating ownership or control over the business website.'
            ],
            [
                'name' => 'Payment Gateway Information',
                'is_for_entity' => false,
                'is_required' => false,
                'business_type' => NtrBusinessType::ECOMMERCE,
                'description' => 'Evidence of the payment gateway selected above.'
            ],
        ];


        foreach ($attachments as $attachment) {
            NtrBusinessAttachmentType::updateOrCreate(
                [
                    'name' => $attachment['name'],
                ],
                [
                    'name' => $attachment['name'],
                    'is_for_entity' => $attachment['is_for_entity'],
                    'is_required' => $attachment['is_required'],
                    'business_type' => $attachment['business_type']
                ]
            );
        }

    }
}
