<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessFile;
use App\Models\TaxAgent;
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

    public function getAgentFile($agentId, $type)
    {

        $agent = TaxAgent::find($agentId);
        if ($type == 'csv') {
            return Storage::disk('local-admin')->response($agent->cv);
        }
        if ($type == 'passport_photo') {
            return Storage::disk('local-admin')->response($agent->passport_photo);
        }

        if ($type == 'tin_certificate') {
            return Storage::disk('local-admin')->response($agent->tin_certificate);
        }

        if ($type == 'emp_letter') {
            return Storage::disk('local-admin')->response($agent->emp_letter);
        }

        if ($type == 'academic_certificate') {
            foreach ($agent->academics as $row)
            {
                return Storage::disk('local-admin')->response($row->certificate);
            }
        }

        if ($type == 'pro_certificate') {
            foreach ($agent->trainings as $row)
            {
                return Storage::disk('local-admin')->response($row->attachment);
            }
        }

        if ($type == 'tra_certificate') {
            foreach ($agent->professionals as $row)
            {
                return Storage::disk('local-admin')->response($row->attachment);
            }
        }


        return abort(404);
    }
}
