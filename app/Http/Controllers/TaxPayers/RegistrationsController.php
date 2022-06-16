<?php

namespace App\Http\Controllers\TaxPayers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegistrationsController extends Controller
{
    public function index(){
        return view('taxpayers.registrations.index');
    }
}
