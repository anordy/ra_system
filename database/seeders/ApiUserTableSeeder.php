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
        ];

        foreach ($data as $row) {
            ApiUser::updateOrCreate([
                'app_name' => $row['app_name'],
                'app_url' => $row['app_url'],
                'username' => $row['username'],
            ], $row);
        }
    }
}
