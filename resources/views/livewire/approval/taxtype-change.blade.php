<div class="card mb-2 bg-white">
    <div class="card-body m-0 pb-0">

        <table class="table table-striped table-sm">
            <thead>
                <th style="width: 30%">Old Values</th>
                <th style="width: 50%">New Values</th>
                <th style="width: 20%">Status</th>
            </thead>
            <tbody>
                <tr>
                    <td>
                        @foreach ($oldTaxTypes as $type)
                            {{ $this->getTaxNameById($type['tax_type_id']) }} - {{ $type['currency'] }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($selectedTaxTypes as $type)
                            {{ $this->getTaxNameById($type['tax_type_id']) }} - {{ $type['currency'] }}<br>
                        @endforeach
                    </td>
                    @if ($taxchange->old_taxtype == $taxchange->new_taxtype)
                        <td class="table-primary">Unchanged</td>
                    @else
                        <td class="table-success">Changed</td>
                    @endif
                </tr>
            </tbody>
        </table>

        @if ($this->checkTransition('registration_manager_review'))
            <h6>Tax Type Change Configurations</h6>

            <div class="row mt-4 mb-4">
                @foreach ($selectedTaxTypes as $key => $value)
                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-label">Current Tax Type</label>
                            <select class="form-control" wire:model="oldTaxTypes.{{ $key }}.tax_type_id"
                                disabled>
                                @foreach ($taxTypes as $tax)
                                    <option value="{{ $tax->id }}">{{ $tax->name }} -
                                        {{ $oldTaxTypes[$key]['currency'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">New Tax Type</label>
                            <select
                                class="form-control @error("selectedTaxTypes.{$key}.tax_type_id") is-invalid @enderror"
                                wire:model="selectedTaxTypes.{{ $key }}.tax_type_id">
                                @foreach ($taxTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @error("selectedTaxTypes.{$key}.tax_type_id")
                                <span class="text-danger error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">New Tax Type Currency</label>
                            <select class="form-control @error("selectedTaxTypes.{$key}.currency") is-invalid @enderror"
                                wire:model="selectedTaxTypes.{{ $key }}.currency">
                                <option value="TZS">Tanzania Shillings</option>
                                <option value="USD">United State Dollar</option>
                            </select>
                            @error("selectedTaxTypes.{$key}.currency")
                                <span class="text-danger error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endforeach
            </div>

        @endif

        @include('livewire.approval.transitions')



    </div>
    @if ($this->checkTransition('registration_manager_review'))
    <div class="row mt-2">
        <div class="col-md-12 mb-3">
            <div class="form-group">
                <label for="exampleFormControlTextarea1">Comments</label>
                <textarea class="form-control @error('comments') is-invalid @enderror" wire:model='comments' rows="3"></textarea>

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
                wire:click="reject('registration_manager_reject')">Reject</button>
            <button class="btn btn-primary" wire:click="approve('registration_manager_review')"
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
