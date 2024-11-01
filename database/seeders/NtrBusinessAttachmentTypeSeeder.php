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
                'business_type' => NtrBusinessType::NON_RESIDENT
            ],
            [
                'name' => 'Certificate of Incorporation/Business License',
                'is_for_entity' => true,
                'is_required' => true,
                'business_type' => NtrBusinessType::NON_RESIDENT
            ],
            [
                'name' => 'Proof of Business Address',
                'is_for_entity' => false,
                'is_required' => false,
                'business_type' => NtrBusinessType::NON_RESIDENT
            ],
            [
                'name' => 'TIN',
                'is_for_entity' => false,
                'is_required' => false,
                'business_type' => NtrBusinessType::NON_RESIDENT
            ],
            [
                'name' => 'Power of Attorney',
                'is_for_entity' => false,
                'is_required' => false,
                'business_type' => NtrBusinessType::NON_RESIDENT
            ],
            [
                'name' => 'Bank Account Information',
                'is_for_entity' => false,
                'is_required' => false,
                'business_type' => NtrBusinessType::NON_RESIDENT
            ],
            [
                'name' => 'Business Incorporation Certificate',
                'is_for_entity' => false,
                'is_required' => true,
                'business_type' => NtrBusinessType::ECOMMERCE
            ],
            [
                'name' => 'Website Ownership Proof (Optional)',
                'is_for_entity' => false,
                'is_required' => false,
                'business_type' => NtrBusinessType::ECOMMERCE
            ],
            [
                'name' => 'Payment Gateway Information (Optional)',
                'is_for_entity' => false,
                'is_required' => false,
                'business_type' => NtrBusinessType::ECOMMERCE
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
