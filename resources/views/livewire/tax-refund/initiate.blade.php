<div class="container-fluid mb-sm-4">

    @include('layouts.component.messages')

    <div class="card text-left rounded-0">
        <div class="card-body">
            <div class="p-3">
                <h3>Initiate Tax Refund on Importation</h3>
                <hr/>
                <div class="row">

                    <div class="form-group col-lg-4">
                        <label class="control-label">Select Port Location *</label>
                        <select class="form-control" wire:model.lazy="portId">
                            <option value="" selected>Choose option</option>
                            @foreach($ports as $port)
                                <option value="{{ $port->id  }}">{{ $port->name  }}</option>
                            @endforeach
                        </select>
                        @error('portId')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-lg-4">
                        <label class="control-label">Is Taxpayer ZRA Registered? *</label>
                        <select class="form-control" wire:model.lazy="isZraRegistered">
                            <option value="" selected>Choose option</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        @error('isZraRegistered')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    @if($isZraRegistered === '1')
                        <div class="form-group col-md-4">
                            <label>Location ZTN Number *</label>
                            <input type="text" wire:model.defer="ztnNumber"
                                   class="form-control @error('ztnNumber') is-invalid @enderror">
                            @error('ztnNumber')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @elseif($isZraRegistered === '0')
                        <div class="form-group col-md-4">
                            <label>Importer Name *</label>
                            <input type="text" wire:model.defer="importerName"
                                   class="form-control @error('importerName') is-invalid @enderror">
                            @error('importerName')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label>Phone Number *</label>
                            <input type="text" wire:model.defer="phoneNumber"
                                   class="form-control @error('phoneNumber') is-invalid @enderror">
                            @error('phoneNumber')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <div class="form-group col-lg-4">
                        <label class="control-label">Does Taxpayer have Refund Documents? *</label>
                        <select class="form-control" wire:model.lazy="hasRefundDocument">
                            <option value="" selected>Choose option</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        @error('hasRefundDocument')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-danger mr-1" wire:click="clear">
                            <i class="bi bi-x-circle mr-1"></i>
                            Clear
                        </button>
                        <button class="btn btn-primary rounded-0" wire:click="proceed()" wire:loading.attr="disable">
                            <i class="bi bi-arrow-return-right mr-2" wire:loading.remove wire:target="proceed"></i>
                            <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                               wire:target="proceed"></i>
                            Proceed
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($hasRefundDocument === '1')
        <div class="card mt-3">
            <div class="card-header font-weight-bold bg-white">
                Imported Goods
            </div>
            <div class="card-body">
                @foreach($allItems as $i => $item)
                    <div class="row mx-3">
                        <div class="form-group col-md-3">
                            <label>TANSAD Number *</label>
                            <input type="text" wire:model.defer="allItems.{{$i}}.tansad_number"
                                   class="form-control @error('allItems.' . $i . '.tansad_number') is-invalid @enderror">
                            @error('allItems.' . $i . '.tansad_number')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label>EFD Number *</label>
                            <input type="text" wire:model.defer="allItems.{{$i}}.efd_number"
                                   class="form-control @error('allItems.' . $i . '.efd_number') is-invalid @enderror">
                            @error('allItems.' . $i . '.efd_number')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label>Amount *</label>
                            <input type="text" disabled wire:model.defer="allItems.{{$i}}.excl_tax_amount"
                                   class="form-control @error('allItems.' . $i . '.excl_tax_amount')) is-invalid @enderror">
                            @error('allItems.' . $i . '.excl_tax_amount')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            @if(count($allItems) > 1)
                                <button class="btn btn-outline-danger mt-4" wire:click="removeItem({{ $i }})">
                                    <i class="bi bi-x-circle-fill mr-2"></i>
                                    Remove
                                </button>
                            @endif

                            @if((count($allItems) - 1) == $i)
                                <button class="btn btn-success mr-2 mt-4" wire:click="addItem({{ $i  }})"
                                        wire:loading.class="disabled">
                                    <i class="bi bi-plus-circle-fill mr-2"></i>
                                    Add Imported Good
                                </button>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-12">
                        <hr/>
                    </div>
                @endforeach

                <div class="row mx-3">
                    <div class="col-md-12">
                        Total Amount Excluding Tax: {{ number_format($totalPayableAmount, 2)}}
                    </div>
                    <div class="col-md-12">
                        Total Payable Tax: {{ number_format($totalPayableAmount, 2)}} * 15% = {{ number_format($totalPayableAmount * 0.15, 2)  }}
                    </div>
                    <div class="col-md-12">
                        <hr/>
                    </div>
                </div>

                <div class="row m-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <button class="btn btn-danger ml-1" wire:click="clear">
                            <i class="bi bi-x-circle mr-1"></i>
                            Clear
                        </button>
                        <button class="btn btn-primary ml-1" wire:click="submit">
                            <i class="bi bi-send mr-1" wire:loading.remove wire:target="submit"></i>
                            <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                               wire:target="submit"></i>
                            Generate Control Number
                        </button>
                    </div>
                </div>
            </div>

        </div>
    @endif

    @if($hasRefundDocument === '0')
        <div class="card mt-3">
            <div class="card-header font-weight-bold bg-white">
                Imported Goods
            </div>
            <div class="card-body">
                @foreach($allItems as $i => $item)
                    <div class="row mx-3">
                        <div class="form-group col-md-3">
                            <label>TANSAD Number *</label>
                            <input type="text" wire:model.defer="allItems.{{$i}}.tansad_number"
                                   class="form-control @error('allItems.' . $i . '.tansad_number') is-invalid @enderror">
                            @error('allItems.' . $i . '.tansad_number')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label>EFD Number *</label>
                            <input type="text" wire:model.defer="allItems.{{$i}}.efd_number"
                                   class="form-control @error('allItems.' . $i . '.efd_number') is-invalid @enderror">
                            @error('allItems.' . $i . '.efd_number')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label>Amount *</label>
                            <input type="text" disabled wire:model.defer="allItems.{{$i}}.excl_tax_amount"
                                   class="form-control @error('allItems.' . $i . '.excl_tax_amount')) is-invalid @enderror">
                            @error('allItems.' . $i . '.excl_tax_amount')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            @if(count($allItems) > 1)
                                <button class="btn btn-outline-danger mt-4" wire:click="removeItem({{ $i }})">
                                    <i class="bi bi-x-circle-fill mr-2"></i>
                                    Remove
                                </button>
                            @endif

                            @if((count($allItems) - 1) == $i)
                                <button class="btn btn-success mr-2 mt-4" wire:click="addItem({{ $i  }})"
                                        wire:loading.class="disabled">
                                    <i class="bi bi-plus-circle-fill mr-2"></i>
                                    Add Imported Good
                                </button>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-12">
                        <hr/>
                    </div>
                @endforeach

                <div class="row mx-3">
                    <div class="col-md-12">
                        Total Amount Excluding Tax: {{ number_format($totalPayableAmount, 2)}}
                    </div>
                    <div class="col-md-12">
                        Total Payable Tax: {{ number_format($totalPayableAmount, 2)}} * 15% = {{ number_format($totalPayableAmount * 0.15, 2)  }}
                    </div>
                    <div class="col-md-12">
                        <hr/>
                    </div>
                </div>

                <div class="row m-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <button class="btn btn-danger ml-1" wire:click="clear">
                            <i class="bi bi-x-circle mr-1"></i>
                            Clear
                        </button>
                        <button class="btn btn-primary ml-1" wire:click="submit">
                            <i class="bi bi-send mr-1" wire:loading.remove wire:target="submit"></i>
                            <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                               wire:target="submit"></i>
                            Generate Control Number
                        </button>
                    </div>
                </div>
            </div>

        </div>
    @endif
</div>


