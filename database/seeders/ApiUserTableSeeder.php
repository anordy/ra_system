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
                'username' => 'immigration',
                'password' => Hash::make('password'),
                'status' => true,
            ],
        ];

        foreach ($data as $row) {
            ApiUser::updateOrCreate($row);
        }
    }
}
