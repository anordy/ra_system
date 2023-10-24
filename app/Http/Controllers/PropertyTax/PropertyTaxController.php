<?php

namespace App\Http\Controllers\PropertyTax;

use App\Http\Controllers\Controller;
use App\Models\PropertyTax\Property;

class PropertyTaxController extends Controller
{

    public function index() {
        return view('property-tax.index');
    }

    public function show(string $id) {
        $property = Property::findOrFail(decrypt($id));
        return view('property-tax.show', compact('property'));
    }

}