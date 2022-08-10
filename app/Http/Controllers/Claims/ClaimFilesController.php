<?php

namespace App\Http\Controllers\Claims;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClaimFilesController extends Controller
{
    public function show($path)
    {
        if ($path) {
            try {
                return Storage::disk('local-admin')->response(decrypt($path));
            } catch (Exception $e) {
                report($e);
                abort(404);
            }
        }

        return abort(404);
    }
}
