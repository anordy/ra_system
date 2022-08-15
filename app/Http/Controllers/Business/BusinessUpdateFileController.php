<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\BusinessUpdate;
use Illuminate\Support\Facades\Storage;

class BusinessUpdateFileController extends Controller
{
    public function getContractFile($updateId)
    {
        $file = BusinessUpdate::find($updateId);
        return Storage::disk('local-admin')->response($file->agent_contract);
    }
}
