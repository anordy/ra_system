<div>
    <table class="table table-striped table-lg">
        <thead>
        <th>From Tax Type</th>
        <th>To Tax Type</th>
        <th>Status</th>
        </thead>
        <tbody>
        <tr>
            <td>
                    <span>{{ $taxchange->fromTax->name ?? 'N/A' }} -
                        {{ $taxchange->from_tax_type_currency ?? '' }}</span><br>
            </td>
            <td>
                @if ($taxchange->toTax && $taxchange->toTax->code == 'vat')
                    {{ $taxchange->subvat ? $taxchange->subvat->name : 'SUBVAT_NOTSET_ERR' }}
                @else
                    <span>{{ $taxchange->toTax->name ?? 'N/A' }} -
                        {{ $taxchange->to_tax_type_currency ?? '' }}</span><br>
                @endif
            </td>
            <td class="@if ($taxchange->from_tax_type_id != $taxchange->to_tax_type_id) table-success @endif">
                @if ($taxchange->from_tax_type_id == $taxchange->to_tax_type_id)
                    <span>Unchanged</span><br>
                @else
                    <span>Changed</span><br>
                @endif
            </td>
        </tr>
        </tbody>
    </table>

    @if($taxchange->status === \App\Models\BusinessStatus::APPROVED)
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Payment per year</label>
                    <input type="text" class="form-control" value="{{ $selectedAnnualSale['annual_estimate'] ?? 0 }}" disabled>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Payment per three months </label>
                    <input type="text" class="form-control" value="{{ $selectedAnnualSale['quarter_estimate'] ?? 0 }}" disabled>
                </div>
            </div>
        </div>
    @endif

    <table class="table table-lg mt-2">
        <tbody>
        <tr>
            <th>Reason for Changing Tax Type:</th>
            <td>{{ $taxchange->reason }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td><span class="badge badge-warning">{{ ucfirst($taxchange->status ?? 'N/A') }}</span></td>
        </tr>
        @if ($taxchange->effective_date)
            <tr>
                <th>Effective Date</th>
                <td>{{ $taxchange->effective_date->toFormattedDateString() }}</td>
            </tr>
        @endif
        <tr>
            <th>Request By</th>
            <td>{{ $taxchange->taxpayer->full_name }}</td>
        </tr>
        <tr>
            <th>Request Date</th>
            <td>{{ $taxchange->created_at->toFormattedDateString() }}</td>
        </tr>
        </tbody>
    </table>




    @if ($this->checkTransition('registration_manager_review'))
        <h6>Tax Type Change Configuration</h6>
        <hr>
        <div class="row mt-2 mb-4">
            <div class="col-6">
                <div class="form-group">
                    <label class="form-label">From Tax Type</label>
                    <input class="form-control" type="text" disabled value="{{ $taxchange->fromTax->name }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">To Tax Type</label>
                    <select class="form-control @error('to_tax_type_id') is-invalid @enderror"
                            wire:model="to_tax_type_id">
                        @foreach ($taxTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('to_tax_type_id')
                    <span class="text-danger error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            @if ($showLumpsumOptions === true)
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Annual Sales</label>
                        <select class="form-control @error("lumpSumAnnualSaleId") is-invalid @enderror" wire:model="lumpSumAnnualSaleId">
                            <option selected>--Select---</option>
                            @foreach($annualSales ?? [] as $annualSale)
                                <option value="{{$annualSale->id}}"> {{number_format($annualSale->min_sales_per_year ?? 0,2)}}
                                    - {{ number_format($annualSale->max_sales_per_year ?? 0,2)}} </option>
                            @endforeach
                        </select>
                        @error("lumpSumAnnualSaleId")
                        <span class="text-danger error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Payment per year</label>
                        <input type="text" class="form-control" value="{{ $selectedAnnualSale['annual_estimate'] ?? 0 }}" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Payment per three months </label>
                        <input type="text" class="form-control" value="{{ $selectedAnnualSale['quarter_estimate'] ?? 0 }}" disabled>
                    </div>
                </div>

            @endif

            @if ($showSubVatOptions === true)
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">VAT Category Type</label>
                        <select
                                class="form-control @error("sub_vat_id") is-invalid @enderror"
                                wire:model="sub_vat_id">
                            <option value="" selected disabled>--Select---</option>
                            @foreach ($subVatOptions as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                            @endforeach
                        </select>
                        @error("sub_vat_id")
                        <span class="text-danger error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endif
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">To Tax Type Currency</label>
                    <select class="form-control @error('to_tax_type_currency') is-invalid @enderror"
                            wire:model="to_tax_type_currency">
                        <option value="TZS">Tanzania Shillings</option>
                        <option value="USD">United State Dollar</option>
                    </select>
                    @error('to_tax_type_currency')
                    <span class="text-danger error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    @endif

    @include('livewire.approval.transitions')

    @if ($this->checkTransition('registration_manager_review'))
        <div class="row mt-2">
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Comments</label>
                    <textarea class="form-control @error('comments') is-invalid @enderror" wire:model.defer='comments'
                              rows="3"></textarea>

                    @error('comments')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="modal-footer p-2 m-0">
            <button type="button" class="btn btn-danger"
                    wire:click="confirmPopUpModal('reject', 'registration_manager_reject')">Reject
            </button>
            <button class="btn btn-primary" wire:click="confirmPopUpModal('approve', 'registration_manager_review')"
                    wire:loading.attr="disable">
                <i class="bi bi-arrow-return-right mr-2" wire:loading.remove
                   wire:target="approve('registration_manager_review')"></i>
                <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                   wire:target="approve('registration_manager_review')"></i>
                Approve
            </button>
        </div>
    @endif
</div>
