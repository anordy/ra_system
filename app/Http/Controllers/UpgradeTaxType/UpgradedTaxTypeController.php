<?php

namespace App\Http\Controllers\UpgradeTaxType;

use App\Http\Controllers\Controller;
use App\Models\BusinessTaxTypeChange;
use Illuminate\Http\Request;

class UpgradedTaxTypeController extends Controller
{
    public function index()
    {
        return view('upgrade-tax-type.upgraded.index');
    }

    public function show($id)
    {
        $tax_type_change = BusinessTaxTypeChange::query()->findOrFail(decrypt($id));
        return view('upgrade-tax-type.upgraded.show', compact('tax_type_change'));
    }
}
