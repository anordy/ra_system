<?php

namespace App\Http\Controllers\PublicService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeRegistrationsController extends Controller
{
    // Todo: Add permissions
    public function index(){
        return view('public-service.de-registrations.index');
    }
}
