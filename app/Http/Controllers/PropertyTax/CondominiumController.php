<?php

namespace App\Http\Controllers\PropertyTax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CondominiumController extends Controller
{

    public function register() {
        return view('property-tax.condominium.register');
    }

    public function index() {
        return view('property-tax.condominium.index');
    }

    public function show(string $id) {
        return view('property-tax.condominium.show');
    }
}
