<?php

namespace App\Http\Livewire\Payments;

use App\Jobs\Payments\FinalizeBankRecon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class BankReconImport extends Component
{
    use LivewireAlert, WithFileUploads;

    public $reconFile, $path;

    protected $rules = [
        'reconFile' => 'required|file|mimes:csv,xls,xlsx',
    ];

    protected $messages = [
        'reconFile.required' => 'Please provide a file to import.',
        'reconFile.mimes' => 'The file must be a valid csv, xls or xlsx.'
    ];

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

        } catch (\Exception $exception){
            $this->alert('error', $exception->getMessage());
            DB::rollBack();
            Log::error($exception);
        }
    }

    public function render()
    {
        return view('livewire.payments.bank-recon-import');
    }
}
