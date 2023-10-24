<?php

namespace App\Http\Controllers\PropertyTax;

use App\Http\Controllers\Controller;
use App\Models\PropertyTax\Condominium;
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
        $condominium = Condominium::findOrFail(decrypt($id));
        return view('property-tax.condominium.show', compact('condominium'));
    }

    public function edit(string $id) {
        return view('property-tax.condominium.edit', compact('id'));
    }
}
