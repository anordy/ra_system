<div class="card p-4">
    <div class="card-body">
        <h3>{{ __('Security Questions') }}</h3>
        <p>{{ __('Please complete and remember the security questions that you choose and provide answers to, these will be used to recover your reference number or help access your account.') }}.</p>
        <hr />
        <div class="form-group row mb-2">
            <label class="col-sm-2 col-form-label font-weight-bold">Question No. 1</label>
            <div class="col-sm-10 form-group mb-0">
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
            <label class="col-sm-2 col-form-label font-weight-bold">Answer</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('firstAns') is-invalid @enderror"
                       placeholder="{{ $firstAnsFlag ? '*********' : '' }}"
                       wire:model.defer="firstAns">
                @error('firstAns')
                <span class="invalid-feedback">{{  $message }}</span>
                @enderror
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-2 col-form-label font-weight-bold">Question No. 2</label>
            <div class="col-sm-10 form-group mb-0">
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
            <label class="col-sm-2 col-form-label font-weight-bold">Answer</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('secondAns') is-invalid @enderror"
                       wire:model.defer="secondAns"
                       placeholder="{{ $secondAnsFlag ? '*********' : '' }}">
                @error('secondAns')
                <span class="invalid-feedback">{{  $message }}</span>
                @enderror
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-2 col-form-label font-weight-bold">Question No. 3</label>
            <div class="col-sm-10 form-group mb-0">
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
            <label class="col-sm-2 col-form-label font-weight-bold">Answer</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('thirdAns') is-invalid @enderror"
                       wire:model.defer="thirdAns"
                       placeholder="{{ $thirdAnsFlag ? '*********' : '' }}">
                @error('thirdAns')
                <span class="invalid-feedback">{{  $message }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                <button class="btn btn-primary" wire:click="submit">
                    <i class="mr-2" wire:loading.remove wire:target="submit"></i>
                    <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading wire:target="submit"></i>
                    {{ __('Update Changes') }}
                </button>
            </div>
        </div>
    </div>
</div>
