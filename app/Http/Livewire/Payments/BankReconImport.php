<?php

namespace App\Http\Livewire\Payments;

use App\Jobs\Payments\FinalizeBankRecon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class BankReconImport extends Component
{
    use LivewireAlert, WithFileUploads;

    public $reconFile, $path;

    protected $rules = [
        'reconFile' => 'required|file', // Accept .txt since php confuses .txt for csv sometimes
    ];

    protected $messages = [
        'reconFile.required' => 'Please provide a file to import.',
        'reconFile.mimes' => 'The file must be a valid csv, xls or xlsx.'
    ];

    public function downloadTemplate(){
        if (config('app.env') == 'production'){
            return response()->download(public_path('templates/prod-template.csv'));
        }
        return response()->download(public_path('templates/bank-reconciliations.csv'));
    }

    public function submit()
    {
        $this->validate();

        $this->path = $this->reconFile->store('bank-reconciliations');

        try {
            DB::beginTransaction();

            Excel::import(new \App\Imports\BankReconImport(), $this->path);

            DB::commit();

            dispatch(new FinalizeBankRecon());

            $this->alert('success', 'Importing complete.');

            return redirect(request()->header('Referer'));

        } catch (ValidationException $exception){
            DB::rollBack();
            foreach ($exception->failures() as $error) {
                $this->alert('error', 'Error at row ' . $error->row() . '. ' . $error->errors()[0], ['timer' => 12000]);
            }
            Log::error($exception->failures());
        }
        catch (\Exception $exception){
            DB::rollBack();
            Log::error($exception);
            $this->alert('error', $exception->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.payments.bank-recon-import');
    }
}
