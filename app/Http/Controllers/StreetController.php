<?php

namespace App\Http\Controllers;

use App\Models\Street;
use App\Traits\CustomAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class StreetController extends Controller
{
    use CustomAlert;
    public function index()
    {
        if (!Gate::allows('setting-street-view')) {
            abort(403);
        }

        return view('settings.street');
    }

    public function downloadSampleSheet()
    {
        if (!Gate::allows('setting-street-add')) {
            abort(403);
        }

        return response()->download(public_path('templates/streets.csv'));
    }

    public function uploadBulk(Request $request)
    {
        if (!Gate::allows('setting-street-add')) {
            abort(403);
        }
        $request->validate([
            'file' => 'required|file|mimes:csv|max:2048',
        ]);

        if ($request->file('file')->isValid()) {
            $uploadedFile = $request->file('file');
            $path = $uploadedFile->path();
            $lines = [];

            if (($handle = fopen($path, 'r')) !== false) {
                $headers = fgetcsv($handle, 1000, ",");

                if (strtolower(trim($headers[0])) != 'ward' || strtolower(trim($headers[1])) != 'street') {
                    fclose($handle);
                    session()->flash('error', 'File columns do not match the expected columns.');
                    return redirect()->back();
                }

                try {
                    DB::beginTransaction();
                    $lineCount = 1;
                    while (($line = fgetcsv($handle, 1000, ",")) !== false) {
                        $lineCount++;
                        $lines[] = $line;
                        $wardName = strtolower(trim($line[0]));
                        $streetName = strtolower(trim($line[1]));
                        $ward = DB::table('wards')
                            ->whereRaw('LOWER(name) LIKE ?', ["%{$wardName}%"])
                            ->first();
                        if (!$ward) {
                            DB::rollBack();
                            session()->flash('error', 'On line ' . $lineCount . ' of the uploaded file, ward "' . $wardName . '" does not exists. Please try to check the spelling or create the ward first');
                            return redirect()->back();
                        } else {
                            $street = DB::table('streets')
                                ->whereRaw('LOWER(name) LIKE ?', ["%{$streetName}%"])
                                ->where('ward_id', $ward->id)
                                ->first();
                            if (!$street) {
                                Street::create([
                                    'ward_id' => $ward->id,
                                    'name' => ucwords($streetName),
                                ]);
                            }
                        }
                    }
                    DB::commit();
                    session()->flash('success', 'Streets imported successfully');
                } catch (\Throwable $th) {
                    session()->flash('error', 'Failed to import the file');
                    Log::error($th);
                    DB::rollBack();
                }
                fclose($handle);
            }

            return redirect()->back();
        }

        return redirect()->back()->with('error', 'File upload failed.');
    }
}
