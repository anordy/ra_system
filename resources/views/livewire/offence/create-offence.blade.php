<div class="container-fluid mb-sm-4">
   <div class="card text-left rounded-0">
      <div class="card-body">
         <div class="p-3">
            <h3>{{ __('Offence Registration') }}</h3>
            <p>{{ __('Please provide all the required information to continue') }}.</p>
            <hr />

            <!-- Option to ask for Znumber -->
            <div class="form-group">
               <label>{{ __('Do you have a Znumber?') }}</label>
               <div>
                  <label class="radio-inline mr-3">
                     <input type="radio" name="has_znumber" value="yes" wire:model="hasZnumber"> {{ __('Yes') }}
                  </label>
                  <label class="radio-inline">
                     <input type="radio" name="has_znumber" value="no" wire:model="hasZnumber"> {{ __('No') }}
                  </label>
               </div>
            </div>

            <!-- Conditional input box for Znumber -->
            @if ($hasZnumber == 'yes')
               <div class="form-group">
                  <label class="font-weight-bold">{{ __('Znumber') }} *</label>
                  <div class="input-group">
                     <input type="text" id="znumber" class="form-control" wire:model="znumber">
                     <div class="input-group-append">
                        <button class="btn btn-success" type="button" wire:click="fetchBusinessDetails" wire:loading.attr="disabled">
                           <span wire:loading wire:target="fetchBusinessDetails" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                           <span wire:loading.remove wire:target="fetchBusinessDetails">{{ __('Fetch') }}</span>
                        </button>
                     </div>
                  </div>
                  @error('znumber')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            @endif

            <!-- Fields for other information -->
            @if($hasZnumber)
               <div class="row">
                  <div class="form-group col-md-4">
                     <label class="font-weight-bold">{{ __('Debtor name') }} *</label>
                     <input type="text" id="name" class="form-control" wire:model.lazy="name" @if($hasZnumber == 'yes') readonly @endif>
                     @error('name')
                     <span class="text-danger">{{ $message }}</span>
                     @enderror
                  </div>
                  <div class="form-group col-md-4">
                     <label class="font-weight-bold">{{ __('Debtor Mobile') }}  *</label>
                     <input type="text" id="mobile" class="form-control" wire:model.lazy="mobile" @if($hasZnumber == 'yes') readonly @endif>
                     @error('mobile')
                     <span class="text-danger">{{ $message }}</span>
                     @enderror
                  </div>
                  <div class="form-group col-md-4">
                     <label class="font-weight-bold">{{ __('Amount') }}  *</label>
                     <input type="text" id="amount" class="form-control" wire:model.lazy="amount" >
                     @error('amount')
                     <span class="text-danger">{{ $message }}</span>
                     @enderror
                  </div>

                  <div class="form-group col-md-4">
                     <label class="font-weight-bold">{{ __('Tax Type') }} *</label>
                     <select class="form-control @error('taxType') is-invalid @enderror" wire:model.lazy="taxType" >
                        <option value="" >{{ __('Please choose') }}...</option>
                        @foreach($taxTypes as $category)
                           <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                     </select>
                     @error('taxType')
                     <span class="text-danger">{{ $message }}</span>
                     @enderror
                  </div>

                  @if(count($subVats ?? []) > 0)
                     <div class="form-group col-md-4">
                        <label class="font-weight-bold">{{ __('Vat Category') }} *</label>
                        <select class="form-control @error('sub_vat_id') is-invalid @enderror" wire:model.lazy="sub_vat_id" >
                           <option value="" >{{ __('Please choose') }}...</option>
                           @foreach($subVats as $sub)
                              <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                           @endforeach
                        </select>
                        @error('sub_vat_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                     </div>
                  @endif

                  <div class="form-group col-md-4">
                     <label class="font-weight-bold">{{ __('Currency') }} *</label>
                     <select class="form-control @error('currency') is-invalid @enderror" wire:model.lazy="currency" >
                        <option value="" >{{ __('Please choose') }}...</option>
                        @foreach($currencies as $currencyOption)
                           <option value="{{ $currencyOption->iso }}">{{ $currencyOption->name }}</option>
                        @endforeach
                     </select>
                     @error('currency')
                     <span class="text-danger">{{ $message }}</span>
                     @enderror
                  </div>
               </div>
            @endif

            <div class="col-md-12 text-right">
               <button class="btn btn-primary rounded-0" wire:click="submit" wire:loading.attr="disabled">
                  <i class="bi bi-arrow-return-right mr-2" wire:loading.remove wire:target="submit"></i>
                  <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading wire:target="submit"></i>
                  {{ __('Save') }}
               </button>
            </div>
         </div>
      </div>
   </div>
</div>
