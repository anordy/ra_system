<?php

namespace App\Http\Livewire\Payments;

use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class BankReconImport extends Component
{
    use LivewireAlert, WithFileUploads;

    public $reconFile, $path = 'bank-reconciliations/UkC5kLRNp15QtJmsMvdNj9FE4IbMECpv1KvJKuZ3.csv';

    protected $rules = [
        'reconFile' => 'required|file|mimes:csv',
    ];

    public function submit()
    {
//        $this->validate();

//        $this->path = $this->reconFile->store('bank-reconciliations');

        Log::info('Importing');

        Excel::import(new \App\Imports\BankReconImport(), $this->path);
    }

    public function render()
    {
        return view('livewire.payments.bank-recon-import');
    }
}
