<div class="container-fluid mb-sm-4">

    <div class="card text-left rounded-0">
        <div class="card-body">
            <div class="p-3">
                <h3>Search Business Information</h3>
                <p>Search Business Information by Z Number or Name.</p>
                <hr/>
                <div class="row">

                    <div class="form-group col-md-4">
                        <label>Query Type: *</label>
                        <select class="form-control" wire:model="queryType">
                            <option></option>
                            <option value="{{ \App\Enum\BusinessQueryType::TAX_TYPE  }}">Tax Type
                            </option>
                        </select>
                        @error('queryType')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label>Search By Type: *</label>
                        <select class="form-control" wire:model="identifierType">
                            <option></option>
                            <option value="{{ \App\Enum\BusinessQueryIdentifierType::ZTN_NUMBER  }}">Ztn Number
                            </option>
                            <option value="{{ \App\Enum\BusinessQueryIdentifierType::BUSINESS_NAME }}">
                                Business Name
                            </option>
                        </select>
                        @error('identifierType')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label>Identifier: *</label>
                        <input type="text" wire:model="identifierData" wire:keydown.enter="search()"
                               class="form-control @error('identifierData') is-invalid @enderror">
                        @error('identifierData')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-12 mt-2">
                        <div class="float-right">
                            <button type="button" class="btn btn-danger mr-1" wire:click="clear">
                                <i class="bi bi-x-circle mr-1"></i>
                                Clear
                            </button>
                            <button class="btn btn-primary rounded-0" wire:click="search()" wire:loading.attr="disable">
                                <i class="bi bi-arrow-return-right mr-2" wire:loading.remove wire:target="search"></i>
                                <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                                   wire:target="search"></i>
                                Search
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    @if(count($businesses ?? []) > 0)
        <div class="card text-left rounded-0">
            <div class="card-body">
                <span>{{ count($businesses) }} business(es) have been found matching the searched criteria</span>
                <hr>
                <table class="table table-sm px-2">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Vendor Name</th>
                        <th>ZTN Number</th>
                        <th>TIN</th>
                        <th>Place of Business</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($businesses as $key => $business)
                        <tr>
                            <td class="px-2">{{ $key+1 }}.</td>
                            <td class="px-2">{{ $business->name ?? 'N/A' }}</td>
                            <td class="px-2">{{ $business->ztn_number ?? 'N/A' }}</td>
                            <td class="px-2">{{ $business->tin?? 'N/A'  }}</td>
                            <td class="px-2">{{ $business->place_of_business ?? 'N/A'  }}</td>
                            <td class="px-2">
                                <button
                                        wire:click="viewBusiness('{{ encrypt($business->id)  }}')"
                                        class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye-fill mr-1"></i> View
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if(isset($businessInfo))
        @if($this->queryType === \App\Enum\BusinessQueryType::TAX_TYPE)
            @include('business.query.includes.base', ['business' => $businessInfo])
        @endif
    @endif


</div>
