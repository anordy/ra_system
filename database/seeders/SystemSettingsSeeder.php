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
        ];

        foreach ($system_settings as $system_setting) {
            SystemSetting::updateOrCreate($system_setting);
        }
    }
}
