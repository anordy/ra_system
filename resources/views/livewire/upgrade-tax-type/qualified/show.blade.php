<div>
    <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist" style="margin-bottom: 0;">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
               aria-selected="true">Return Information</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="approval-tab" data-toggle="tab" href="#approval" role="tab" aria-controls="approval"
               aria-selected="false">Approval History</a>
        </li>
    </ul>

    <div class="tab-content bg-white border shadow-sm" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div>
                <div class="card rounded-0">
                    <div class="card-header d-flex justify-content-between">
                        <div>Return Details</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td class="font-weight-bold text-uppercase">Tax Payer Name</td>
                                        <td class="my-1">{{$return->business->taxpayer->first_name}} {{$return->business->taxpayer->last_name}}</td>
                                    </tr>

                                    <tr>
                                        <td class="font-weight-bold text-uppercase">Business Name</td>
                                        <td class="my-1">{{ $return->business->name  }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-uppercase">Date of Business Commencement</td>
                                        <td class="my-1">{{date('D, Y-m-d',strtotime($return->businessLocation->date_of_commencing)) }}</td>
                                    </tr>

                                    <tr>
                                        <td class="font-weight-bold text-uppercase">Tax Type</td>
                                        <td class="my-1">{{$return->taxtype->name }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td class="font-weight-bold text-uppercase">Phone</td>
                                        <td class="my-1">{{ $return->business->mobile }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-uppercase">Email</td>
                                        <td class="my-1">{{ $return->business->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-uppercase">Current Turnover</td>
                                        <td class="my-1">{{ number_format($sales,2) }} <strong>{{$currency}}</strong></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            @if(!$taxTypeUpgrade)
                                @livewire('upgrade-tax-type.create-modal', ['return' => $return])
                            @endif

                        </div>

                    </div>
                </div>

                @if($taxTypeUpgrade)

                    @if (count($this->getEnabledTransitions()) > 1)
                        <div class="card my-2 mx-2 bg-white rounded-0">
                            <div class="card-header font-weight-bold bg-white">
                                Tax Type Upgrade Approval
                            </div>
                            <div class="card-body">
                                @include('livewire.approval.transitions')
                            </div>

                            @if($this->checkTransition('registration_officer_review'))
                                @include('upgrade-tax-type.qualified.registration_officer_review')
                            @endif

                            @if($this->checkTransition('registration_manager_review'))
                                @include('upgrade-tax-type.qualified.registration_manager_review')
                            @endif

                        </div>
                    @endif
                @endif


            </div>

        </div>

        <div class="tab-pane fade m-2" id="approval" role="tabpanel" aria-labelledby="approval-tab">

            @if($taxTypeUpgrade)
                <livewire:approval.approval-history-table modelName='App\Models\BusinessTaxTypeUpgrade'
                                                          modelId="{{ encrypt($taxTypeUpgrade->id) }}" />
            @else
                <span>No Approval History Recorded, Please initiate Tax Type upgrade approval to see history</span>
            @endif

        </div>
    </div>


</div>
