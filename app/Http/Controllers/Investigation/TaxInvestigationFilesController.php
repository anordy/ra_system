<?php


namespace App\Http\Controllers\Investigation;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TaxInvestigationFilesController extends Controller
{
    public function show($path)
    {
        if ($path) {
            try {
                return Storage::disk('local')->response(decrypt($path));
            } catch (Exception $e) {
                Log::error('Error: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return redirect()->back()->withError('Something went wrong. Please contact your admin.');
            }
        }

        return abort(404);
    }
}
