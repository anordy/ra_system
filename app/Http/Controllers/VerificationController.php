<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VerificationController extends Controller
{
    public function tin($businessId)
    {
        $businessId =  decrypt($businessId);

        $business = Business::findOrFail($businessId);
        $tin      = $business->tin;
        
        // $response = Http::get('https://<ipaddress>:<port>/api/taxpayer', [
        //     'apiKey' => 'YOUR_API_KEY_HERE',
        //     'limit'  => 10,
        // ]); Used to fetch from TRA api

        $response   = json_decode(file_get_contents(public_path() . '\api\TIN.json'), true);
       
        if (array_key_exists($tin, $response)) {
            $taxpayer = $response[$tin];

            //other verification if necessary
            $verified = 'verified';
        } else {
            $verified = ' TIN number does not exist';
        }
        
        return view('business.registrations.approval', compact('business', 'verified'));
    }
}
