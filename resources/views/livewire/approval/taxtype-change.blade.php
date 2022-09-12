<div>
<table class="table table-striped table-lg">
    <thead>
        <th style="width: 30%">From Tax Type</th>
        <th style="width: 50%">To Tax Type</th>
        <th style="width: 20%">Status</th>
    </thead>
    <tbody>
        <tr>
            <td>
                <span style="font-size: 13px">{{ $taxchange->fromTax->name }} -
                    {{ $taxchange->from_tax_type_currency ?? '' }}</span><br>
            </td>
            <td>
                <span style="font-size: 13px">{{ $taxchange->toTax->name }} -
                    {{ $taxchange->to_tax_type_currency ?? '' }}</span><br>
            </td>
            <td class="@if ($taxchange->from_tax_type_id != $taxchange->to_tax_type_id) table-success @endif">
                @if ($taxchange->from_tax_type_id == $taxchange->to_tax_type_id)
                    <span style="font-size: 13px">Unchanged</span><br>
                @else
                    <span style="font-size: 13px">Changed</span><br>
                @endif
            </td>
        </tr>
    </tbody>
</table>

<table class="table table-striped table-lg mt-2">
    <tbody>
        <tr>
            <th style="width: 30%">Reason for Changing Tax Type:</th>
            <td>{{ $taxchange->reason }}</td>
        </tr>
    </tbody>
</table>

@if ($this->checkTransition('registration_manager_review'))
    <h6>Tax Type Change Configuration</h6>
    <hr>

    <div class="row mt-2 mb-4">
        <div class="col-4">
            <div class="form-group">
                <label class="form-label">From Tax Type</label>
                <input class="form-control" type="text" disabled value="{{ $taxchange->fromTax->name }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">To Tax Type</label>
                <select class="form-control @error('to_tax_type_id') is-invalid @enderror" wire:model="to_tax_type_id">
                    @foreach ($taxTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
                @error('to_tax_type_id')
                    <span class="text-danger error">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
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
        <button type="button" class="btn btn-danger" wire:click="reject('registration_manager_reject')">Reject</button>
        <button class="btn btn-primary" wire:click="approve('registration_manager_review')" wire:loading.attr="disable">
            <i class="bi bi-arrow-return-right mr-2" wire:loading.remove
                wire:target="approve('registration_manager_review')"></i>
            <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                wire:target="approve('registration_manager_review')"></i>
            Approve
        </button>
    </div>
@endif
</div>