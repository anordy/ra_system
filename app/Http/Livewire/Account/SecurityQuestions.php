<?php

namespace App\Http\Livewire\Account;

use App\Models\Security\Question;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class SecurityQuestions extends Component
{
    use CustomAlert;

    public $questions;
    public $firstOptions, $secondOptions, $thirdOptions;
    public $firstQn, $secondQn, $thirdQn, $firstAns, $secondAns, $thirdAns;
    public $firstAnsFlag, $secondAnsFlag, $thirdAnsFlag;
    public $pre = false;

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

        // Get users qns and answers
        $taxpayer = Auth::user();

        if ($taxpayer->userAnswers()->count() >= 3){
            $this->firstQn = $taxpayer->userAnswers()->where('order', 1)->first()->question_id;
            $this->secondQn = $taxpayer->userAnswers()->where('order', 2)->first()->question_id;
            $this->thirdQn = $taxpayer->userAnswers()->where('order', 3)->first()->question_id;

            $this->firstAnsFlag = $this->firstQn ? true : false;
            $this->secondAnsFlag = $this->secondQn ? true : false;
            $this->thirdAnsFlag = $this->thirdQn ? true : false;
        }

        // Update question selections
        $this->updatedFirstQn();
        $this->updatedSecondQn();
        $this->updatedThirdQn();
    }

    public function submit(){
        $this->validate();

        $taxpayer = Auth::user();

        // Update or Create
        $taxpayer->userAnswers()->where('order', 1)->updateOrCreate(['order' => 1], [
            'question_id' => $this->firstQn,
            'answer' => Hash::make($this->firstAns),
            'order' => 1
        ]);

        $taxpayer->userAnswers()->where('order', 2)->updateOrCreate(['order' => 2], [
            'question_id' => $this->secondQn,
            'answer' => Hash::make($this->secondAns),
            'order' => 2
        ]);

        $taxpayer->userAnswers()->where('order', 3)->updateOrCreate(['order' => 3], [
            'question_id' => $this->thirdQn,
            'answer' => Hash::make($this->thirdAns),
            'order' => 3
        ]);

        session()->flash('success', __('Your security questions were updated successful.'));
        $this->redirect(route('account.security-questions'));
    }

    public function updatedFirstQn(){
        $this->secondOptions = Question::query()->whereNotIn('id', [$this->firstQn ?? 0, $this->thirdQn ?? 0])->get();
        $this->thirdOptions = Question::query()->whereNotIn('id', [$this->secondQn ?? 0, $this->firstQn ?? 0])->get();
    }

    public function updatedSecondQn(){
        $this->firstOptions = Question::query()->whereNotIn('id', [$this->secondQn ?? 0, $this->thirdQn ?? 0])->get();
        $this->thirdOptions = Question::query()->whereNotIn('id', [$this->secondQn ?? 0, $this->firstQn ?? 0])->get();
    }

    public function updatedThirdQn(){
        $this->firstOptions = Question::query()->whereNotIn('id', [$this->secondQn ?? 0, $this->thirdQn ?? 0])->get();
        $this->thirdOptions = Question::query()->whereNotIn('id', [$this->firstQn ?? 0, $this->secondQn ?? 0])->get();
    }

    public function render(){
        if ($this->pre){
            return view('livewire.account.pre-security-questions');
        }
        return view('livewire.account.security-questions');
    }
}
