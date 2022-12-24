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
                'description' => 'is a set of rules that determine the complexity and strength of passwords used on our system. The goal of a password policy is to ensure that users choose strong, unique passwords that are difficult for unauthorized individuals to guess or crack.',
            ]
        ];

        foreach ($categories as $category) {
            SystemSettingCategory::updateOrCreate($category);
        }

        $system_settings = [
            [
                'system_setting_category_id' => 1,
                'name' => 'Password expiration duration',
                'code' => 'password-expiration-duration',
                'description' => 'The amount of time after which a password must be changed',
                'value' => '60',
                'unit' => 'days',
            ],
            [
                'system_setting_category_id' => 1,
                'name' => 'Password history number',
                'code' => 'password-histroy-number',
                'description' => 'is a feature of a password policy that prevents users from reusing their previous passwords. The purpose of password history is to ensure that users are not able to simply switch back to an old password after changing it, as this would not increase the security of the system',
                'value' => '3',
                'unit' => 'number',
            ],
        ];

        foreach ($system_settings as $system_setting) {
            SystemSetting::updateOrCreate($system_setting);
        }
    }
}
