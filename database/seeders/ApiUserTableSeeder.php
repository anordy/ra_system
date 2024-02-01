<?php
namespace Database\Seeders;

use App\Models\ApiUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ApiUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        // UAT USERS
        if (env('APP_ENV') != 'local'){
            $data = [
                [

                    'app_name' => 'Test',
                    'app_url' => 'localhost',
                    'username' => 'ZIDRASAPI',
                    'password' => Hash::make('Z1dras@API'),
                    'status' => true,
                ],
                [

                    'app_name' => 'VFMS Test',
                    'app_url' => 'localhost vfms',
                    'username' => 'VFMSAPI',
                    'password' => Hash::make('VFMS1@API'),
                    'status' => true,
                ],
                [

                    'app_name' => config('modulesconfig.api_server_username'),
                    'app_url' => "http://127.0.0.1:8000/api/v1",
                    'username' => config('modulesconfig.api_server_username'),
                    'password' => Hash::make(config('modulesconfig.api_server_password')),
                    'status' => true,
                ],
            ];

        } else {
            $data = [
                [

                    'app_name' => 'Test',
                    'app_url' => 'localhost',
                    'username' => 'ZIDRASAPI',
                    'password' => Hash::make('Z1dras@API'),
                    'status' => true,
                ],
                [

                    'app_name' => 'VFMS Test',
                    'app_url' => 'localhost vfms',
                    'username' => 'VFMSAPI',
                    'password' => Hash::make('VFMS1@API'),
                    'status' => true,
                ]
            ];
        }

        foreach ($data as $row) {
            ApiUser::updateOrCreate(
                [
                'app_name' => $row['app_name'],
                'app_url' => $row['app_url'],
                'username' => $row['username'],
            ], $row);
        }
    }
}
