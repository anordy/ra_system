<?php

namespace Database\Seeders;

use App\Models\BusinessCategory;
use App\Models\BusinessFileType;
use App\Models\FileType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessFileTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'license',
                'business_type' => BusinessCategory::SOLE,
            ],
            [
                'short_name' => 'license',
                'name' => 'Business License',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::SOLE,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'recognition_letter',
                'business_type' => BusinessCategory::SOLE,
            ],
            [
                'short_name' => 'recognition_letter',
                'name' => 'Licence/certificate from control board of the business',
                'description' => 'If the business is governed by recognized body, a letter from the Body governing the activity',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::SOLE,
                'is_required' => false,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'tin',
                'business_type' => BusinessCategory::SOLE,
            ],
            [
                'short_name' => 'tin',
                'name' => 'Taxpayer TIN Certificate',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::SOLE,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        // Partnership Deed Agreement
        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'partnership_deed',
                'business_type' => BusinessCategory::PARTNERSHIP,
            ],
            [
                'short_name' => 'partnership_deed',
                'name' => 'Partnership Deed Agreement',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::PARTNERSHIP,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'business_license',
                'business_type' => BusinessCategory::PARTNERSHIP,
            ],
            [
                'short_name' => 'business_license',
                'name' => 'Business License',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::PARTNERSHIP,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'recognition_letter',
                'business_type' => BusinessCategory::PARTNERSHIP,
            ],
            [
                'short_name' => 'recognition_letter',
                'name' => 'Licence/certificate from control board of the business',
                'description' => 'If the business is governed by recognized body, a letter from the Body governing the activity',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::PARTNERSHIP,
                'is_required' => false,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'tin',
                'business_type' => BusinessCategory::PARTNERSHIP,
            ],
            [
                'short_name' => 'tin',
                'name' => 'Partnership TIN Certificate',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::PARTNERSHIP,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        // Company
        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'memorandum',
                'business_type' => BusinessCategory::COMPANY,
            ],
            [
                'short_name' => 'memorandum',
                'name' => 'Memorandum and Article of Association',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::COMPANY,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'certificate_of_compliance',
                'business_type' => BusinessCategory::COMPANY,
            ],
            [
                'short_name' => 'certificate_of_compliance',
                'name' => 'Certificate of incorporation/Compliance',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::COMPANY,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'business_license',
                'business_type' => BusinessCategory::COMPANY,
            ],
            [
                'short_name' => 'business_license',
                'name' => 'Business License',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::COMPANY,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'tin',
                'business_type' => BusinessCategory::COMPANY,
            ],
            [
                'short_name' => 'tin',
                'name' => 'Company TIN Certificate',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::COMPANY,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        // NGO
        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'ngo_constitution',
                'business_type' => BusinessCategory::NGO,
            ],
            [
                'short_name' => 'ngo_constitution',
                'name' => 'NGO Constitution',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::NGO,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'application_letter',
                'business_type' => BusinessCategory::NGO,

            ],
            [
                'short_name' => 'application_letter',
                'name' => 'Application Letter for Registration',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::NGO,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'tin',
                'business_type' => BusinessCategory::NGO,
            ],
            [
                'short_name' => 'tin',
                'name' => 'TIN Certificate for the NGO',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::NGO,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'business_license',
                'business_type' => BusinessCategory::NGO,
            ],
            [
                'short_name' => 'business_license',
                'name' => 'Business License',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::NGO,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'recognition_letter',
                'business_type' => BusinessCategory::COMPANY,
            ],
            [
                'short_name' => 'recognition_letter',
                'name' => 'Licence/certificate from control board of the business',
                'description' => 'If the business is governed by recognized body, a letter from the Body governing the activity',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::COMPANY,
                'is_required' => false,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'consolidated_form',
                'business_type' => BusinessCategory::COMPANY,
            ],
            [
                'short_name' => 'consolidated_form',
                'name' => 'BPRA Consolidated Form',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::COMPANY,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        // General/All
        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'lease_agreement',
                'business_type' => BusinessCategory::OTHER,
            ],
            [
                'short_name' => 'lease_agreement',
                'name' => 'Lease Agreement',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::OTHER,
                'is_required' => false,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'power_of_attorney',
                'business_type' => BusinessCategory::SOLE,
            ],
            [
                'short_name' => 'power_of_attorney',
                'name' => 'Power of Attorney (Instrument)',
                'description' => 'If the business is being registered on behalf of the owner, Power of attorney (Instrument) is required.',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::SOLE,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'power_of_attorney',
                'business_type' => BusinessCategory::PARTNERSHIP,
            ],
            [
                'short_name' => 'power_of_attorney',
                'name' => 'Power of Attorney (Instrument)',
                'description' => 'If the business is being registered on behalf of the owner, Power of attorney (Instrument) is required.',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::PARTNERSHIP,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'power_of_attorney',
                'business_type' => BusinessCategory::COMPANY,
            ],
            [
                'short_name' => 'power_of_attorney',
                'name' => 'Power of Attorney (Instrument)',
                'description' => 'If the business is being registered on behalf of the owner, Power of attorney (Instrument) is required.',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::COMPANY,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'power_of_attorney',
                'business_type' => BusinessCategory::NGO,
            ],
            [
                'short_name' => 'power_of_attorney',
                'name' => 'Power of Attorney (Instrument)',
                'description' => 'If the business is being registered on behalf of the owner, Power of attorney (Instrument) is required.',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::NGO,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'lease_agreement',
                'business_type' => BusinessCategory::SOLE,
            ],
            [
                'short_name' => 'lease_agreement',
                'name' => 'Lease Agreement',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::SOLE,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'lease_agreement',
                'business_type' => BusinessCategory::PARTNERSHIP,

            ],
            [
                'short_name' => 'lease_agreement',
                'name' => 'Lease Agreement',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::PARTNERSHIP,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'lease_agreement',
                'business_type' => BusinessCategory::COMPANY,
            ],
            [
                'short_name' => 'lease_agreement',
                'name' => 'Lease Agreement',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::COMPANY,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'lease_agreement',
                'business_type' => BusinessCategory::NGO,
            ],
            [
                'short_name' => 'lease_agreement',
                'name' => 'Lease Agreement',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::NGO,
                'is_required' => true,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'registration_certificate',
                'business_type' => BusinessCategory::SOLE,
            ],
            [
                'short_name' => 'registration_certificate',
                'name' => 'Registration Certificate of Business Name',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::SOLE,
                'is_required' => false,
                'is_approved' => 1,
            ]
        );

        BusinessFileType::updateOrCreate(
            [
                'short_name' => 'consolidated_form',
                'business_type' => BusinessCategory::SOLE,
            ],
            [
                'short_name' => 'consolidated_form',
                'name' => 'BPRA Consolidated Form',
                'file_type' => FileType::PDF,
                'business_type' => BusinessCategory::SOLE,
                'is_required' => false,
                'is_approved' => 1,
            ]
        );
    }
}
