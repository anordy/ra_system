<div class="container-fluid mb-sm-4">
   <div class="card text-left rounded-0">
      <div class="card-body">
         <div class="p-3">
            <h3>{{ __('Offence Registration') }}</h3>
            <p>{{ __('Please provide all the required information to continue') }}.</p>
            <hr />
            <div class="row">

               <div class="form-group col-md-4">
                  <label class="font-weight-bold">{{ __('Debtor name') }} *</label>
                  <input type="text"  id="name" class="form-control" wire:model.lazy="name" required>
                  @error('name')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
               <div class="form-group col-md-4">
                  <label class="font-weight-bold">{{ __('Debtor Mobile') }}  *</label>
                  <input type="text" id="mobile" class="form-control" wire:model.lazy="mobile" required>
                  @error('mobile')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
               <div class="form-group col-md-4">
                  <label class="font-weight-bold">{{ __('Amount') }}  *</label>
                  <input type="text"  id="amount" class="form-control" wire:model.lazy="amount" required>
                  @error('amount')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>

               <div class="form-group col-md-6">
                  <label class="font-weight-bold">{{ __('Tax Type') }} *</label>
                     <select class="form-control @error('taxTypes') is-invalid @enderror" wire:model.lazy="taxType"   required>
                     <option value="" >{{ __('Please choose') }}...</option>
                     @foreach($taxTypes as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                     @endforeach
                  </select>
                  @error('businessType')
                     <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>

               <div class="form-group col-md-6">
                  <label class="font-weight-bold">{{ __('Currency') }} *</label>
                  <select class="form-control @error('currencies') is-invalid @enderror" wire:model.lazy="currency" >
                     <option value="" >{{ __('Please choose') }}...</option>
                     @foreach($currencies as $currency)
                        <option value="{{ $currency->iso }}">{{ $currency->name }}</option>
                     @endforeach
                  </select>
                  @error('currency')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>








               <div class="col-md-12 text-right">
                  <button class="btn btn-primary rounded-0" wire:click="submit" wire:loading.attr="disable">
                     <i class="bi bi-arrow-return-right mr-2" wire:loading.remove wire:target="submit"></i>
                     <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading wire:target="submit"></i>
                     {{ __('Save') }}
                  </button>
               </div>
            </div>
         </div>
      </div>
   </div>

</div>