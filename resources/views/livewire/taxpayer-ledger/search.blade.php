<div class="container-fluid mb-sm-4">

    @include('layouts.component.messages')

    @if(!$showLedgers)
        <div class="card text-left rounded-0">
            <div class="card-body">
                <div class="p-3">
                    <h3>Search Taxpayer Account</h3>
                    <p>Search Taxpayer ledger account by Z Number.</p>
                    <hr/>
                    <div class="row">

                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">ZTN Number</label>
                            <input type="text" wire:model.defer="ztnNumber" wire:keydown.enter="search()"
                                   class="form-control @error('ztnNumber') is-invalid @enderror">
                        </div>

                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Start Date</label>
                            <input type="date" wire:model.defer="startDate"
                                   class="form-control @error('startDate') is-invalid @enderror">
                        </div>

                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">End Date</label>
                            <input type="date" wire:model.defer="endDate"
                                   class="form-control @error('endDate') is-invalid @enderror">
                        </div>

                        <div class="col-md-6 form-group">
                            <label class="d-flex justify-content-between font-weight-bold">
                                <span>Tax Type</span>
                            </label>
                            <select class="form-control" wire:model="taxTypeId">
                                <option value="{{ \App\Enum\ReportStatus::All  }}">All</option>
                                @if(!empty($taxTypes))
                                    @foreach ($taxTypes as $taxType)
                                        <option value="{{ $taxType->id }}">
                                            {{ $taxType->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="col-md-12 text-right">
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

                @if(count($accounts) > 0)
                    <hr>
                    <table class="table table-sm table-borderless px-2">
                        <thead>
                        <tr>
                            <th>Business Name</th>
                            <th>ZTN Number</th>
                            <th>TIN</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="px-2">{{ $business->name ?? 'N/A' }}</td>
                            <td class="px-2"> {{ $business->ztn_number ?? ''  }}</td>
                            <td class="px-2"> {{ $business->tin ?? ''  }}</td>
                            <td class="px-2">
                                @if(isset($business->id))
                                    <button wire:click="getLedgersByBusiness({{ $business->id  }}, '{{ $taxTypeId ?? \App\Enum\ReportStatus::All }}')"
                                            class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye-fill mr-1"></i> View Account
                                    </button>
                                    <a href="{{ route('finance.taxpayer.ledger.business-summary', ['businessId' => encrypt($business->id)]) }}"
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye-fill mr-1"></i> View Taxpayer Business Summary
                                    </a>
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr>
                    <table class="table table-sm px-2">
                        <label class="font-weight-bold">Branch/Location Results</label>
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Branch Name</th>
                            <th>ZTN Location Number</th>
                            <th>Tax Type Account</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($accounts as $key => $account)
                            <tr>
                                <td class="px-2">{{ $key+1 }}.</td>
                                <td class="px-2">{{ $account->location->name ?? 'N/A' }}</td>
                                <td class="px-2">{{ $account->location->zin ?? 'N/A' }}</td>
                                <td class="px-2">{{ $account->taxtype->name ?? 'N/A'  }}</td>
                                <td class="px-2">
                                    @if(isset($account->location->id))
                                        <button wire:click="getLedgers({{ $account->location->id  }}, '{{ $account->tax_type_id ?? \App\Enum\ReportStatus::All }}')"
                                                class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye-fill mr-1"></i> View Account
                                        </button>
                                        <a href="{{ route('finance.taxpayer.ledger.summary', ['businessLocationId' => encrypt($account->location->id)]) }}"
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye-fill mr-1"></i> View Taxpayer Branch Summary
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    @if($ztnNumber)
                        <span class="text-center">No results found</span>
                    @endif
                @endif

            </div>
        </div>
    @endif

    @if($showLedgers && $ledgers)
        <div class="bg-light clearfix mb-2">
            <span></span>
            <button wire:click="back()" class="btn float-right main-color btn-sm px-3">
                <i class="bi bi-arrow-left pr-2"></i>Back
            </button>
        </div>
        @include('taxpayer-ledger.ledgers', ['ledgers' => $ledgers])
    @endif

</div>