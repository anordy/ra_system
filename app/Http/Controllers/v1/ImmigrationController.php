<?php


namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;

class ImmigrationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function getPassportData($passportNumber, $permitNumber) {
        $response   = json_decode(file_get_contents(public_path() . '/api/Immigration.json'), true);
        // TODO: Simulate if no passport or permit number found
        return $response['data'][0];

    }

}
