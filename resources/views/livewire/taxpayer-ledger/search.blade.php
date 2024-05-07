<div class="container-fluid mb-sm-4">

    @include('layouts.component.messages')

    <div class="card text-left rounded-0">
        <div class="card-body">
            <div class="p-3">
                <h3>Search Taxpayer Account</h3>
                <p>Search Taxpayer ledger account by Z Number, Business Name etc.</p>
                <hr/>
                <div class="row">

                    <div class="form-group col-md-6">
                        <label class="font-weight-bold">Business Name.</label>
                        <input type="text" wire:model.defer="businessName" class="form-control @error('businessName') is-invalid @enderror">
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

            @if($businesses)
                <table class="table table-sm px-2">
                    <label class="font-weight-bold">Search Results</label>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Business Name</th>
                            <th>TIN</th>
                            <th>Tax Type Account</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($businesses as $key => $business)
                        <tr>
                            <td class="px-2">{{ $key+1 }}.</td>
                            <td class="px-2">{{ $business->name ?? 'N/A' }}</td>
                            <td class="px-2">{{ $business->tin ?? 'N/A' }}</td>
                            <td class="px-2"></td>
                            <td class="px-2">
{{--                                <a href="{{ route('finance.taxpayer.ledger.show', [encrypt($business->id)]) }}" class="btn btn-outline-primary btn-sm">--}}
{{--                                    <i class="bi bi-eye-fill mr-1"></i> View Account--}}
{{--                                </a>--}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <span class="text-center">No results found</span>
            @endif

        </div>
    </div>


</div>