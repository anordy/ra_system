<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BusinessFileController extends Controller
{
    public function index()
    {
        return view('settings.business-files');
    }

    public function getBusinessFile($fileId){
        $file = BusinessFile::findOrFail(decrypt($fileId));

        // Check who can access the file
        if ($file){
            return Storage::response($file->location);
        }

        // If they dont meet requirements, abort
        return abort(404);
    }
}
