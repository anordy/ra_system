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
        BusinessFileType::updateOrCreate([
            'short_name' => 'title_deed',
            'name' => 'Title deed or lease agreement',
            'description' => 'Title deed for the premises or lease agreement of which stamp duty has been paid.',
            'file_type' => FileType::PDF,
            'business_type' => BusinessCategory::SOLE,
            'is_required' => true
        ]);

        BusinessFileType::updateOrCreate([
            'short_name' => 'license',
            'name' => 'Business License',
            'file_type' => FileType::PDF,
            'business_type' => BusinessCategory::SOLE,
            'is_required' => true
        ]);

        BusinessFileType::updateOrCreate([
            'short_name' => 'recognition_letter',
            'name' => 'Recognition Letter',
            'description' => 'If the business is governed by recognized body, a letter from the Body governing the activity',
            'file_type' => FileType::PDF,
            'business_type' => BusinessCategory::SOLE,
            'is_required' => false
        ]);

        // Partnership Deed Agreement
        BusinessFileType::updateOrCreate([
            'short_name' => 'partnership_deed',
            'name' => 'Partnership Deed Agreement',
            'file_type' => FileType::PDF,
            'business_type' => BusinessCategory::PARTNERSHIP,
            'is_required' => true
        ]);

        BusinessFileType::updateOrCreate([
            'short_name' => 'title_deed',
            'name' => 'Title deed or lease agreement',
            'description' => 'Title deed for the premises or lease agreement of which stamp duty has been paid.',
            'file_type' => FileType::PDF,
            'business_type' => BusinessCategory::PARTNERSHIP,
            'is_required' => true
        ]);

        BusinessFileType::updateOrCreate([
            'short_name' => 'business_license',
            'name' => 'Business License',
            'file_type' => FileType::PDF,
            'business_type' => BusinessCategory::PARTNERSHIP,
            'is_required' => true
        ]);

        BusinessFileType::updateOrCreate([
            'short_name' => 'recognition_letter',
            'name' => 'Recognition Letter',
            'description' => 'If the business is governed by recognized body, a letter from the Body governing the activity',
            'file_type' => FileType::PDF,
            'business_type' => BusinessCategory::PARTNERSHIP,
            'is_required' => false
        ]);

        // Company
        BusinessFileType::updateOrCreate([
            'short_name' => 'memorandum',
            'name' => 'Memorandum and Article of Association',
            'file_type' => FileType::PDF,
            'business_type' => BusinessCategory::COMPANY,
            'is_required' => true
        ]);

        BusinessFileType::updateOrCreate([
            'short_name' => 'title_deed',
            'name' => 'Title deed or lease agreement',
            'description' => 'Title deed for the premises or lease agreement of which stamp duty has been paid.',
            'file_type' => FileType::PDF,
            'business_type' => BusinessCategory::COMPANY,
            'is_required' => true
        ]);

        BusinessFileType::updateOrCreate([
            'short_name' => 'certificate_of_compliance',
            'name' => 'Certificate of incorporation/Compliance',
            'file_type' => FileType::PDF,
            'business_type' => BusinessCategory::COMPANY,
            'is_required' => true
        ]);

        BusinessFileType::updateOrCreate([
            'short_name' => 'recognition_letter',
            'name' => 'Recognition Letter',
            'description' => 'If the business is governed by recognized body, a letter from the Body governing the activity',
            'file_type' => FileType::PDF,
            'business_type' => BusinessCategory::COMPANY,
            'is_required' => false
        ]);

        BusinessFileType::updateOrCreate([
            'short_name' => 'business_license',
            'name' => 'Business License',
            'file_type' => FileType::PDF,
            'business_type' => BusinessCategory::COMPANY,
            'is_required' => true
        ]);

        // NGO
        BusinessFileType::updateOrCreate([
            'short_name' => 'ngo_constitution',
            'name' => 'NGO Constitution',
            'file_type' => FileType::PDF,
            'business_type' => BusinessCategory::NGO,
            'is_required' => true
        ]);

        BusinessFileType::updateOrCreate([
            'short_name' => 'application_letter',
            'name' => 'Application Letter for Registration',
            'file_type' => FileType::PDF,
            'business_type' => BusinessCategory::NGO,
            'is_required' => true
        ]);

        BusinessFileType::updateOrCreate([
            'short_name' => 'title_deed',
            'name' => 'Title deed or lease agreement',
            'description' => 'Title deed for the premises or lease agreement of which stamp duty has been paid.',
            'file_type' => FileType::PDF,
            'business_type' => BusinessCategory::NGO,
            'is_required' => true
        ]);

        BusinessFileType::updateOrCreate([
            'short_name' => 'tin',
            'name' => 'TIN Certificate for the NGO',
            'file_type' => FileType::PDF,
            'business_type' => BusinessCategory::NGO,
            'is_required' => true
        ]);

        BusinessFileType::updateOrCreate([
            'short_name' => 'business_license',
            'name' => 'Business License',
            'file_type' => FileType::PDF,
            'business_type' => BusinessCategory::NGO,
            'is_required' => true
        ]);

        BusinessFileType::updateOrCreate([
            'short_name' => 'recognition_letter',
            'name' => 'Recognition Letter',
            'description' => 'If the business is governed by recognized body, a letter from the Body governing the activity',
            'file_type' => FileType::PDF,
            'business_type' => BusinessCategory::COMPANY,
            'is_required' => false
        ]);

        BusinessFileType::updateOrCreate([
            'short_name' => 'consolidated_form',
            'name' => 'BPRA Consolidated Form',
            'file_type' => FileType::PDF,
            'business_type' => BusinessCategory::COMPANY,
            'is_required' => true
        ]);

        // General/All
        BusinessFileType::updateOrCreate([
            'short_name' => 'lease_agreement',
            'name' => 'Lease Agreement',
            'file_type' => FileType::PDF,
            'business_type' => BusinessCategory::OTHER,
            'is_required' => false
        ]);
    }
}
