<?php


namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class TaxAuditController extends Controller
{
    public function index(Request $request)
    {
        return view('audit.index');
    }
}
