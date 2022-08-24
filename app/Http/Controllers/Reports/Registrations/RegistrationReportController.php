<?php

namespace App\Http\Controllers\Reports\Registrations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegistrationReportController extends Controller
{
    public function index()
    {
        return view('reports.registrations.index');
    }
}
