<div class="row">
    <div class="col-md-4 form-group">
        <label>Payment Type</label>
        <select wire:model="paymentType" class="form-control">
            <option></option>
            <option value="cash">Cash</option>
            <option value="installment">Installment</option>
            <option value="full">Full Payment</option>
        </select>
    </div>

    @if($paymentType === 'installment')
        <div class="col-md-4 form-group">
            <label>Installment Phases</label>
            <input class="form-control" wire:model="installmentCount" placeholder="E.g. 2 phases">
        </div>
    @endif

    <div class="col-md-12 d-flex justify-content-end">
        <button class="btn btn-danger mr-2 pl-3 pr-4 font-weight-bold" wire:click="deny">
            <i class="bi bi-x-circle-fill mr-1"></i>
            Reject
        </button>
        <button class="btn btn-success pl-3 pr-4 font-weight-bold" wire:click="approve">
            <i class="bi bi-check2-all fill mr-1"></i>
            Approve
        </button>
    </div>
</div>