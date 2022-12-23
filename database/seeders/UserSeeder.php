<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::updateOrCreate([
            'fname' => "Super",
            'lname' => "Admin",
            'email' => "admin@gmail.com",
            'phone' => '12323232323',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => 1,
            'status' => true,
            'is_first_login' => false,
        ]);

        User::updateOrCreate([
            'fname' => "Kedmon",
            'lname' => "Joseph",
            'email' => "jkedmon95@gmail.com",
            'phone' => '0675580888',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => 1,
            'status' => true,
            'is_first_login' => false,
        ]);

        User::updateOrCreate([
            'fname' => "Gozbert",
            'lname' => "Stanslaus",
            'email' => "Gozbert.Stanslaus@ubx.co.tz",
            'phone' => '0766583354',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => 1,
            'status' => true,
            'is_first_login' => false,
        ]);

        User::updateOrCreate([
            'fname' => "Lucks",
            'lname' => "Isack",
            'email' => "lucksisack2@gmail.com",
            'phone' => '0759155015',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => 1,
            'status' => true,
            'is_first_login' => false,
        ]);

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
        ]);

        User::updateOrCreate([
            'fname' => "Mang'erere",
            'lname' => "Mgini",
            'email' => "juniorshemm@gmail.com",
            'phone' => '0743317069',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => 1,
            'status' => true,
            'is_first_login' => false,
        ]);

        User::updateOrCreate([
            'fname' => "Noor",
            'lname' => "Noor",
            'email' => "noor.abdulrahim@ubx.co.tz",
            'phone' => '0656731663',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => 1,
            'status' => true,
            'is_first_login' => false,
        ]);

        User::updateOrCreate([
            'fname' => "Victor",
            'lname' => "Massawe",
            'email' => "Victor.Massawe@ubx.co.tz",
            'phone' => '0656642323',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => 1,
            'status' => true,
            'is_first_login' => false,
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
        ]);

        User::updateOrCreate([
            'fname' => "Anord",
            'lname' => "john",
            'email' => "Anord.John@ubx.co.tz",
            'phone' => '0716121689',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => 1,
            'status' => true,
            'is_first_login' => false,
        ]);

        User::updateOrCreate([
            'fname' => "Petro",
            'lname' => "Joseph",
            'email' => "Petro.Joseph@ubx.co.tz",
            'phone' => '0657824541',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => 1,
            'status' => true,
            'is_first_login' => false,
        ]);

        User::updateOrCreate([
            'fname' => "Compliance",
            'lname' => "Officer1",
            'email' => "compliance.officer1@zrb.co.tz",
            'phone' => '0765639173',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => 5,
            'status' => true,
            'is_first_login' => false,
        ]);

        User::updateOrCreate([
            'fname' => "Compliance",
            'lname' => "officer2",
            'email' => "compliance.officer2@ubx.co.tz",
            'phone' => '0765639174',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => 5,
            'status' => true,
            'is_first_login' => false,
        ]);

        User::updateOrCreate([
            'fname' => "Registration",
            'lname' => "officer1",
            'email' => "registration.officer1@ubx.co.tz",
            'phone' => '0765639175',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => 3,
            'status' => true,
            'is_first_login' => false,
        ]);

        User::updateOrCreate([
            'fname' => "Registration",
            'lname' => "officer2",
            'email' => "registration.officer2@ubx.co.tz",
            'phone' => '0765639176',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => 3,
            'status' => true,
            'is_first_login' => false,
        ]);

    }
}
