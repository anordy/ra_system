<nav class="nav nav-tabs mt-0 border-top-0">
    <a href="#tab1" class="nav-item nav-link font-weight-bold active">Withholding Agent Details</a>
    <a href="#tab2" class="nav-item nav-link font-weight-bold">Responsible Persons</a>
</nav>
<div class="tab-content px-2 card pt-3 pb-2">
    <div id="tab1" class="tab-pane fade active show">
        <h6 class="text-uppercase">Main Details</h6>

        <div class="row m-2 pt-3">
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Withholding Agent Number</span>
                <p class="my-1">{{ $withholding_agent->wa_number }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Tax Identification No. (TIN)</span>
                <p class="my-1">{{ $withholding_agent->tin }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Institution Name</span>
                <p class="my-1">{{ $withholding_agent->institution_name }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Address</span>
                <p class="my-1">{{ $withholding_agent->address }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Contact Number</span>
                <p class="my-1">{{ $withholding_agent->mobile }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Email Address</span>
                <p class="my-1">{{ $withholding_agent->email }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Region</span>
                <p class="my-1">{{ $withholding_agent->region->name }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">District</span>
                <p class="my-1">{{ $withholding_agent->district->name }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Ward</span>
                <p class="my-1">{{ $withholding_agent->ward->name }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Date of Commencing</span>
                <p class="my-1">{{ $withholding_agent->date_of_commencing }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Status</span>
                @if ($withholding_agent->status == 'active')
                    <p class="my-1 text-success">Active</p>
                @else
                    <p class="my-1 text-danger">Inactive</p>
                @endif
                </p>
            </div>
        </div>
    </div>
    <div id="tab2" class="tab-pane fade">
        <div class="row mb-2">
            <div class="col-md-6 text-left">
            </div>
            <div class="col-md-6 text-right">
                <button class="btn btn-info"
                    onclick="Livewire.emit('showModal', 'withholding-agents.add-responsible-person-modal',{{ $withholding_agent->id }})">Add Responsible Person
                </button>
            </div>
        </div>
        <livewire:withholding-agents.withholding-agent-responsible-persons-table :id="$withholding_agent->id">
    </div>
</div>

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".nav-tabs a").click(function() {
                $(this).tab('show');
            });
        });
    </script>
@endsection
