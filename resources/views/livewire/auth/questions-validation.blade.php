<div class="text-black p-4 mx-1 mt-5 rounded-lg body-color bg-white">
    <div class="text-center mb-4 mt-3">
        <img src="{{ asset('images/logo.png') }}" id="logo">
    </div>
    <h6 class="mt-2 text-center">Security Questions</h6>
    <p class="text-center text-muted">Please complete the security questions below to log into your account.</p>
    <hr/>
    <div class="form-group row mb-2">
        <label class="col-sm-3 col-form-label font-weight-bold">Question No. 1</label>
        <div class="col-sm-9 form-group mb-0">
            <select class="form-control @error('firstQn') is-invalid @enderror" wire:model="firstQn">
                <option value="">Choose your first question</option>
                @foreach($firstOptions as $question)
                    <option value="{{ $question->id }}">{{ $question->question }}</option>
                @endforeach
            </select>
            @error('firstQn')
            <span class="invalid-feedback">{{  $message }}</span>
            @enderror
        </div>
    </div>
    <div class="form-group row mb-4">
        <label class="col-sm-3 col-form-label font-weight-bold">Answer</label>
        <div class="col-sm-9">
            <input type="text" class="form-control @error('firstAns') is-invalid @enderror" wire:model.defer="firstAns">
            @error('firstAns')
            <span class="invalid-feedback">{{  $message }}</span>
            @enderror
        </div>
    </div>
    <div class="form-group row mb-2">
        <label class="col-sm-3 col-form-label font-weight-bold">Question No. 2</label>
        <div class="col-sm-9 form-group mb-0">
            <select class="form-control @error('secondQn') is-invalid @enderror" wire:model="secondQn">
                <option value="">Choose your second question</option>
                @foreach($secondOptions as $question)
                    <option value="{{ $question->id }}">{{ $question->question }}</option>
                @endforeach
            </select>
            @error('secondQn')
            <span class="invalid-feedback">{{  $message }}</span>
            @enderror
        </div>
    </div>
    <div class="form-group row mb-4">
        <label class="col-sm-3 col-form-label font-weight-bold">Answer</label>
        <div class="col-sm-9">
            <input type="text" class="form-control @error('secondAns') is-invalid @enderror" wire:model.defer="secondAns">
            @error('secondAns')
            <span class="invalid-feedback">{{  $message }}</span>
            @enderror
        </div>
    </div>
    <div class="form-group row mb-2">
        <label class="col-sm-3 col-form-label font-weight-bold">Question No. 3</label>
        <div class="col-sm-9 form-group mb-0">
            <select class="form-control @error('thirdQn') is-invalid @enderror" wire:model="thirdQn">
                <option value="">Choose your first question</option>
                @foreach($thirdOptions as $question)
                    <option value="{{ $question->id }}">{{ $question->question }}</option>
                @endforeach
            </select>
            @error('thirdQn')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label font-weight-bold">Answer</label>
        <div class="col-sm-9">
            <input type="text" class="form-control @error('thirdAns') is-invalid @enderror" wire:model.defer="thirdAns">
            @error('thirdAns')
            <span class="invalid-feedback">{{  $message }}</span>
            @enderror
        </div>
    </div>
    <div class="row pb-2">
        <div class="col-md-12">
            <p class="d-flex justify-content-between align-items-center mt-3 mb-0">
                <a class="text-primary" href="{{ route('session.kill') }}">
                    <i class="bi bi-arrow-90deg-left mr-2"></i>
                    {{ __('Back to login') }}
                </a>
                <button class="btn btn-primary rounded-0 px-4" wire:click="submit" wire:loading.attr="disable">
                    <i class="bi bi-arrow-return-right mr-2" wire:target="submit"></i>
                    Validate
                </button>
            </p>
        </div>
    </div>
</div>
