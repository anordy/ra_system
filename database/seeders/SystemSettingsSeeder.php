<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use App\Models\SystemSettingCategory;
use Illuminate\Database\Seeder;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $categories = [
            [
                'name' => 'Password Policy',
                'code' => 'password-policy',
                'description' => 'is a set of rules that determine the complexity and strength of passwords used on our system. The goal of a password policy is to ensure that users choose strong, unique passwords that are difficult for unauthorized individuals to guess or crack.',
                'is_approved' => 1
            ],
            [
                'name' => 'Login Settings',
                'code' => 'login-settings',
                'description' => 'all settings related to login attempts, lockouts and others.',
                'is_approved' => 1
            ],
            [
                'name' => 'Certificate Settings',
                'code' => 'certificate-settings',
                'description' => 'all settings related Certificate variables.',
                'is_approved' => 1
            ],
            [
                'name' => 'Filing Deadline',
                'code' => 'filing-deadline',
                'description' => 'all settings related to filing deadline',
                'is_approved' => 1
            ],
            [
                'name' => 'Business Settings',
                'code' => 'business-settings',
                'description' => 'all settings related to business settings',
                'is_approved' => 1
            ],
            [
                'name' => 'Filing Minimum Amount',
                'code' => 'filing-minimum-amounts',
                'description' => 'All settings related to filing minimum allowed amounts.',
                'is_approved' => 1
            ],
            [
                'name' => 'Property Tax Amount',
                'code' => 'property-tax-amount',
                'description' => 'All settings related to property tax bill amounts.',
                'is_approved' => 1
            ],
        ];

        foreach ($categories as $category) {
            SystemSettingCategory::updateOrCreate(['name' => $category['name']], $category);
        }

        $system_settings = [
            [
                'system_setting_category_id' => 1,
                'name' => 'Password expiration duration',
                'code' => 'password-expiration-duration',
                'description' => 'The amount of time after which a password must be changed',
                'value' => '60',
                'unit' => 'days',
                'is_approved' => 1
            ],
            [
                'system_setting_category_id' => 2,
                'name' => 'Max Attempts',
                'code' => 'max-login-attempts',
                'description' => 'Maximum login attempts',
                'value' => '3',
                'unit' => 'number',
                'is_approved' => 1
            ],
            [
                'system_setting_category_id' => 2,
                'name' => '2FA Using Security Questions',
                'code' => 'enable-otp-alternative',
                'description' => 'Enable login using security questions as an alternative to OTP.',
                'value' => 0,
                'unit' => SystemSetting::INPUT_OPTIONS,
                'is_approved' => 1,
                'options' => json_encode(['disabled' => 0, 'enabled' => 1])
            ],
            [
                'system_setting_category_id' => 2,
                'name' => 'Decay Minutes',
                'code' => 'login-decay-minutes',
                'description' => 'Maximum duration before user allowed to login again',
                'value' => '60',
                'unit' => 'minutes',
                'is_approved' => 1
            ],
            [
                'system_setting_category_id' => 3,
                'name' => 'General Commissioner Name',
                'code' => 'general-commissioner-name',
                'description' => 'General Commissioner full name',
                'value' => 'YUSUPH JUMA MWENDA',
                'unit' => 'string',
                'is_approved' => 1
            ],
            [
                'system_setting_category_id' => 3,
                'name' => 'General Commissioner Sign',
                'code' => 'general-commissioner-sign',
                'description' => 'General Commissioner sign',
                'value' => '/sign/commissioner.png',
                'unit' => 'file',
                'is_approved' => 1
            ],
            [
                'system_setting_category_id' => 4,
                'name' => 'Filing Deadline Time',
                'code' => 'filing-deadline',
                'description' => 'Filing Deadline Time',
                'value' => '17:00',
                'unit' => 'time',
                'is_approved' => 1
            ],
            [
                'system_setting_category_id' => 5,
                'name' => 'Duration before delete all draft businesses',
                'code' => 'duration-before-delete-draft-businesses',
                'description' => 'Duration before delete all businesses with Draft Status',
                'value' => '7',
                'unit' => 'days',
                'is_approved' => 1
            ],
            [
                'system_setting_category_id' => 2,
                'name' => 'Stamp Duty Minimum Filing Amount',
                'code' => 'stamp-duty-minimum-filling-amount',
                'description' => 'Stamp-duty Composition minimum amount required for filling a return.',
                'value' => '135000',
                'unit' => 'number',
                'is_approved' => 1
            ],
            [
                'system_setting_category_id' => 2,
                'name' => 'Residential Storey Building Tax Amount per unit',
                'code' => SystemSetting::RESIDENTIAL_STOREY_BUILDING,
                'description' => 'Residential Storey Building Tax Amount per storey/unit.',
                'value' => '10000',
                'unit' => 'number',
                'is_approved' => 1
            ],
            [
                'system_setting_category_id' => 2,
                'name' => 'Condominium Property Tax Amount Per Unit',
                'code' => SystemSetting::CONDOMINIUM_BUILDING,
                'description' => 'Condominium tax amount per storey/unit.',
                'value' => '10000',
                'unit' => 'number',
                'is_approved' => 1
            ],
            [
                'system_setting_category_id' => 2,
                'name' => 'Storey Business Building Property Tax Amount',
                'code' => SystemSetting::STOREY_BUSINESS_BUILDING,
                'description' => 'Storey Business Building Property tax amount per storey.',
                'value' => '50000',
                'unit' => 'number',
                'is_approved' => 1
            ],
            [
                'system_setting_category_id' => 2,
                'name' => 'Other Business Building Property Tax Amount',
                'code' => SystemSetting::OTHER_BUSINESS_BUILDING,
                'description' => 'Other Business Building Property tax amount per unit.',
                'value' => '50000',
                'unit' => 'number',
                'is_approved' => 1
            ],
            [
                'system_setting_category_id' => 2,
                'name' => 'Number of Times Interest Is Compounded in Property Tax Per Year',
                'code' => SystemSetting::NUMBER_OF_TIMES_INTEREST_IS_COMPOUNDED_IN_PROPERTY_TAX_PER_YEAR,
                'description' => 'Number of Times Interest Is Compounded in Property Tax Per Year.',
                'value' => '3',
                'unit' => 'number',
                'is_approved' => 1
            ],
            [
                'system_setting_category_id' => 2,
                'name' => 'Property Tax Interest Rate Value',
                'code' => SystemSetting::PROPERTY_TAX_INTEREST_RATE,
                'description' => 'Property Tax Interest Rate Value.',
                'value' => '0.015',
                'unit' => 'number',
                'is_approved' => 1
            ],
            [
                'system_setting_category_id' => 2,
                'name' => 'Property Tax Time Variable',
                'code' => SystemSetting::PROPERTY_TAX_TIME_VARIABLE,
                'description' => 'Property Tax Time Variable.',
                'value' => '0',
                'unit' => 'number',
                'is_approved' => 1
            ],
        ];

        foreach ($system_settings as $system_setting) {
            SystemSetting::updateOrCreate([
                'code' => $system_setting['code']
            ], $system_setting);
        }
    }
}
