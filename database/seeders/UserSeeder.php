<?php

namespace Database\Seeders;

use App\Models\User;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'fname' => "Anord",
                'lname' => "John",
                'email' => "anord.john@crdbbank.co.tz",
                'phone' => '0754711117',
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
                'fname' => "Fredrick",
                'lname' => "Fungamtama",
                'email' => "fredrick.fungamtama@crdbbank.co.tz",
                'phone' => '0754711526',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'status' => true,
                'is_first_login' => false,
                'is_approved' => 1,
                'pass_expired_on' => Carbon::now()->addYear(10)
            ],
            [
                'fname' => "Albert",
                'lname' => "Masanja",
                'email' => "albert.masanja@crdbbank.co.tz",
                'phone' => '0753054442',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'status' => true,
                'is_first_login' => false,
                'is_approved' => 1,
                'pass_expired_on' => Carbon::now()->addYear(10)
            ]
        ];

        foreach ($users as $user) {
            $user['level_id'] = 1;
            User::updateOrCreate($user);
        }
    }
}
