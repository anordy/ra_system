<div>
    <div class="row">
        <div class="col-md-4 mb-2">
            <label>Name</label>
            <input disabled type="text" class="form-control form-control-lg" wire:model="name">
        </div>
        <div class="col-md-4 mb-2">
            <label>Row Type</label>
            <select disabled class="form-control" wire:model="row_type">
                <option value="">--Choose row type--</option>
                <option value="dynamic">Dynamic</option>
                <option value="unremovable">Un-removable</option>
            </select>
        </div>
        <div class="col-md-4 mb-2">
            <label>Column Type</label>
            <select disabled class="form-control" wire:model="col_type">
                <option>--Choose column type--</option>
                <option value="normal">Normal</option>
                <option value="subtotal">Subtotal</option>
                <option value="total">Total</option>
                <option value="grandTotal">Grand Total</option>
            </select>
        </div>

        <div class="col-md-4 mb-2">
            <label>Is Value Calculated?</label>
            <select disabled class="form-control" wire:model="value_calculated">
                <option value="">--Choose--</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
        <div class="col-md-4 mb-2">
            <label>Is Rate Applicable?</label>
            <select disabled class="form-control" wire:model="rate_applicable">
                <option value="">--Choose category--</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
        <div class="col-md-4 mb-2">
            <label>Rate Type</label>
            <select class="form-control" wire:model="rate_type">
                <option value="">--Choose rate type--</option>
                <option value="fixed">Fixed</option>
                <option value="percentage">Percentage</option>
            </select>
        </div>

        <div class="col-md-4 mb-2">
            <label>Rate</label>
            <input type="text" class="form-control form-control-lg" wire:model="rate">
        </div>

        <div class="col-md-4 mb-2">
            <label>Currency</label>
            <select disabled class="form-control" wire:model="currency">
                <option value="">--Choose currency--</option>
                @foreach($currencies as $currency)
                    <option value="{{$currency->iso}}">{{$currency->name}}</option>
                @endforeach
                <option value="BOTH">Both</option>
            </select>
        </div>
        <div class="col-md-4 mb-2">
            <label>Rate USD</label>
            <input disabled type="text" class="form-control form-control-lg" wire:model="rate_usd">
        </div>

        <div class="col-md-12 d-flex justify-content-end">
            @can('setting-return-configuration-edit')
            <button type="button" class="btn btn-success px-5" wire:click='update' wire:loading.attr="disabled">
                <div wire:loading.delay wire:target="update">
                    <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                Update
            </button>
            @endcan
        </div>

    </div>
</div>