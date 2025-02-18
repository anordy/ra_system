<?php

namespace App\Http\Livewire\Account;


use Exception;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Traits\CustomAlert;

class AccountDetails extends Component
{
    use CustomAlert;

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

        // Verify
        if (!$this->verify($this->user)) {
            throw new Exception('Could not verify user account.');
        }

        try {
            $this->user->phone = $this->mobile;
            $this->user->save();

            // Sign
            $this->sign($this->user);

            session()->flash('success', 'Your profile information has been changed successful');
            $this->redirect(route('account'));
        } catch (Exception $e) {
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }


    public function render()
    {
        return view('livewire.account.details');
    }
}
