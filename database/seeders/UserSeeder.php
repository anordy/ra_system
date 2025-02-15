<?php

namespace Database\Seeders;

use App\Models\User;
use App\Traits\VerificationTrait;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    use VerificationTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'fname' => "Meshack",
                'lname' => "Fungo",
                'email' => "meshack.fungo@ubx.co.tz",
                'phone' => '0753550590',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'status' => true,
                'is_first_login' => false,
                'is_approved' => 1,
                'pass_expired_on' => Carbon::now()->addYears(10)
            ],
            [
                'fname' => "Phillip",
                'lname' => "Morro",
                'email' => "phillip.morro@ubx.co.tz",
                'phone' => '0763218007',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'status' => true,
                'is_first_login' => false,
                'is_approved' => 1,
                'pass_expired_on' => Carbon::now()->addYear()
            ],
            [
                'fname' => "Tabitha",
                'lname' => "Mkude",
                'email' => "tabitha.mkude@ubx.co.tz",
                'phone' => '0748570624',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'status' => true,
                'is_first_login' => false,
                'is_approved' => 1,
                'pass_expired_on' => Carbon::now()->addYear()
            ], [
                'fname' => "Asma",
                'lname' => "Hassan",
                'email' => "asma.hassan@zanrevenue.org",
                'phone' => '0772724747',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'status' => true,
                'is_first_login' => false,
                'is_approved' => 1,
                'pass_expired_on' => Carbon::now()->addYear()
            ], [
                'fname' => "Maryam",
                'lname' => "Bundala",
                'email' => "maryam.ramadhan@zanrevenue.org",
                'phone' => '0719606146',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'status' => true,
                'is_first_login' => false,
                'is_approved' => 1,
                'pass_expired_on' => Carbon::now()->addYear()
            ], [
                'fname' => "Amina",
                'lname' => "Barnabas",
                'email' => "amina.charles@zanrevenue.org",
                'phone' => '0777412984',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'status' => true,
                'is_first_login' => false,
                'is_approved' => 1,
                'pass_expired_on' => Carbon::now()->addYear()
            ], [
                'fname' => "SULEIMAN",
                'lname' => "IDDI",
                'email' => "suleiman.iddi@zanrevenue.org",
                'phone' => '0773359471',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'status' => true,
                'is_first_login' => false,
                'is_approved' => 1,
                'pass_expired_on' => Carbon::now()->addYear()
            ], [
                'fname' => "Safia",
                'lname' => "Mzee",
                'email' => "safia.mzee@zanrevenue.org",
                'phone' => '0777490855',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'status' => true,
                'is_first_login' => false,
                'is_approved' => 1,
                'pass_expired_on' => Carbon::now()->addYear()
            ],
            [
                'fname' => "Anord",
                'lname' => "John",
                'email' => "anordy@gmail.com",
                'phone' => '0754711117',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'status' => true,
                'is_first_login' => false,
                'is_approved' => 1,
                'pass_expired_on' => Carbon::now()->addYear()
            ]
        ];

        foreach ($users as $user) {
            $user['level_id'] = 1;
            User::updateOrCreate($user);
        }
    }
}
