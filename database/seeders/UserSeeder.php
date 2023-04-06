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

        User::updateOrCreate([
            'fname' => "Meshack",
            'lname' => "Victor",
            'email' => "meshack.fungo@ubx.co.tz",
            'phone' => '0753550590',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => 1,
            'status' => true,
            'is_first_login' => false,
            'is_approved' => 1,
            'pass_expired_on' => Carbon::now()->addYear()
        ]);

        User::updateOrCreate([
            'fname' => "Mang'erere",
            'lname' => "Mgini",
            'email' => "Mangerere.Mgini@ubx.co.tz",
            'phone' => '0743317069',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => 1,
            'status' => true,
            'is_first_login' => false,
            'is_approved' => 1,
            'pass_expired_on' => Carbon::now()->addYear()
        ]);

        User::updateOrCreate([
            'fname' => "Gerald",
            'lname' => "Njau",
            'email' => "markgerald262@gmail.com",
            'phone' => '0745831971',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => 1,
            'status' => true,
            'is_first_login' => false,
            'is_approved' => 1,
        ]);

        User::updateOrCreate([
            'fname' => "David",
            'lname' => "Mabula",
            'email' => "davidmabux@gmail.com",
            'phone' => '0621749596',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => 1,
            'status' => true,
            'is_first_login' => false,
            'is_approved' => 1,
        ]);

    }
}
