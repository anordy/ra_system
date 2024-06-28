<?php


namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Storage;

class TaxAuditFilesController extends Controller
{
    public function show($path)
    {
        if ($path) {
            try {
                return Storage::disk('local')->response(decrypt($path));
            } catch (Exception $e) {
                report($e);
                session()->flash('warning', 'The selected File was not found. Please contact your administrator');
                return;
            }
        }
    }
}
