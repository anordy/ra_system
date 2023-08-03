<?php

namespace App\Http\Livewire\Auth;

use App\Models\Security\Question;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class QuestionsValidation extends Component
{
    use CustomAlert;

    public $questions;
    public $firstOptions, $secondOptions, $thirdOptions;
    public $firstQn, $secondQn, $thirdQn, $firstAns, $secondAns, $thirdAns;

    protected function rules(): array
    {
        return [
            'firstQn' => 'required|exists:security_questions,id',
            'secondQn' => 'required|exists:security_questions,id',
            'thirdQn' => 'required|exists:security_questions,id',
            'firstAns' => 'required',
            'secondAns' => 'required',
            'thirdAns' => 'required',
        ];
    }

    protected $messages = [
        'firstAns.required' => 'Please provide an answer for your first question.',
        'secondAns.required' => 'Please provide an answer for your second question.',
        'thirdAns.required' => 'Please provide an answer for your third question.',
        'firstQn.required' => 'Please choose your first question',
        'secondQn.required' => 'Please choose your second question',
        'thirdQn.required' => 'Please choose your third question',
    ];

    public function mount(){
        $this->firstOptions = $this->secondOptions = $this->thirdOptions = Question::approved()-> select('id', 'question')->get();
    }

    public function submit(){
        $this->validate();

        // Find user
        $user = Auth::user();

        // Taxpayers answers
        $answers = $user->userAnswers()->whereIn('question_id', [$this->firstQn, $this->secondQn, $this->thirdQn])->get();

        // If answers count is less than 3, wrong select, log out.
        if ($answers->count() < 3){
            Auth::logout();
            session()->flash('error', 'We could not validate your security questions.');
            return redirect()->route('login');
        }

        $formattedAnswers = []; // From DB

        foreach ($answers as $answer) {
            $formattedAnswers[$answer->question_id] = $answer;
        }

        $providedAnswers[$this->firstQn] = $this->firstAns;
        $providedAnswers[$this->secondQn] = $this->secondAns;
        $providedAnswers[$this->thirdQn] = $this->thirdAns;

        // Cross-check
        foreach ($answers as $answer) {
            if (!Hash::check($providedAnswers[$answer->question_id], $answer->answer)){
                Auth::logout();
                session()->flush();
                session()->flash('error', 'We could not validate your security questions.');
                return redirect()->route('login');
            }
        }

        if ($user->is_first_login == true || $user->pass_expired_on <= Carbon::now()) {
            return redirect()->route('password.change');
        } else {
            session()->put('user_2fa', $user->id);
            return redirect()->route('home');
        }
    }

    public function updatedFirstQn($name, $value){
        $this->secondOptions = Question::query()->whereNotIn('id', [$this->firstQn ?? 0, $this->thirdQn ?? 0])->get();
        $this->thirdOptions = Question::query()->whereNotIn('id', [$this->secondQn ?? 0, $this->firstQn ?? 0])->get();
    }

    public function updatedSecondQn($name, $value){
        $this->firstOptions = Question::query()->whereNotIn('id', [$this->secondQn ?? 0, $this->thirdQn ?? 0])->get();
        $this->thirdOptions = Question::query()->whereNotIn('id', [$this->secondQn ?? 0, $this->firstQn ?? 0])->get();
    }

    public function updatedThirdQn($name, $value){
        $this->firstOptions = Question::query()->whereNotIn('id', [$this->secondQn ?? 0, $this->thirdQn ?? 0])->get();
        $this->thirdOptions = Question::query()->whereNotIn('id', [$this->firstQn ?? 0, $this->secondQn ?? 0])->get();
    }

    public function render(){
        return view('livewire.auth.questions-validation');
    }
}
