<?php

namespace App\Http\Livewire\Account;

use Exception;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AccountDetails extends Component
{
    use LivewireAlert;

    public $user, $first_name, $last_name, $email, $mobile;

    protected $rules = [
        'mobile' => 'required|digits_between:10,10'
    ];

    public function mount()
    {
       $this->user = User::findOrFail(Auth::id());
       $this->first_name = $this->user->fname;
       $this->last_name = $this->user->lname;
       $this->email = $this->user->email;
       $this->mobile = $this->user->phone;
    }

    public function makeChanges()
    {
        $this->validate();

        try {
            $this->user->phone = $this->mobile;
            $this->user->save();
            session()->flash('success', 'Your profile information has been changed successful');
            $this->redirect(route('account'));
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }


    public function render()
    {
        return view('livewire.account.details');
    }
}
