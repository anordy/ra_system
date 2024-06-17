@extends('layouts.master')

@section('title', "Tax Return Vetting for {$return->business->name}")

@section('content')
    <div class="row mx-1">
        <div class="col-md-12">
            <livewire:returns.return-payment :return="$return->tax_return" />
        </div>
        {{-- Payment for Port return in USD --}}
        @if ($return_ && $return_->tax_return)
            <div class="col-md-12">
                <livewire:returns.return-payment :return="$return_->tax_return" />
            </div>
        @endif
    
    </div>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                aria-selected="true">Return Information</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                aria-selected="false">Business Information</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact"
               aria-selected="false">Approval Details</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history"
               aria-selected="false">
                Return Edit History
            </a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active card p-2" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            @if (view()->exists($viewRender))
                @php echo view($viewRender, compact('return','return_'))->render() @endphp
                <livewire:approval.tax-returns-vetting-approval-processing modelName="{{ get_class($tax_return) }}" modelId="{{ encrypt($tax_return->id) }}"></livewire:approval.tax-returns-vetting-approval-processing>
            @else
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Error!</h4>
                            <p>
                                Configured page not found kindly check with Administrator
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="tab-pane fade card p-2" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="card mt-2">
                <div class="card-header text-uppercase font-weight-bold bg-white">
                    Business Information
                </div>
                <div class="card-body p-3">
                    <div class="row pt-3">
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Tax Type</span>
                            <p class="my-1">{{ $return->taxtype->name }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Filled By</span>
                            <p class="my-1">{{ $return->taxpayer->full_name ?? '' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Phone Numbers</span>
                            <p class="my-1">{{ $return->taxpayer->mobile ?? '' }} {{ $return->taxpayer->alt_mobile ? '/ '.$return->taxpayer->alt_mobile : '' }} </p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Email</span>
                            <p class="my-1">{{ $return->taxpayer->email ?? '' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Financial Year</span>
                            <p class="my-1">{{ $return->financialYear->name ?? '' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Return Month</span>
                            <p class="my-1">{{ $return->financialMonth->name ?? '' }} {{ $return->financialMonth->year->code ?? '' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Name</span>
                            <p class="my-1">{{ $return->business->name }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Old ZRA Number</span>
                            <p class="my-1">{{ $return->business->previous_zno }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Location</span>
                            <p class="my-1">{{ $return->branch->name ?? 'Head Quarter' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Vetting Status</span>
                            <p class="my-1"> <span class="p-2 badge badge-warning"
                                style="border-radius: 1rem; background: #d1dc3559; color: #474704; font-size: 100%; padding: 5px">
                                {{ $return->vetting_status }}
                            </span></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="tab-pane fade card p-2" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="card">
                <div class="card-body">
                    <livewire:approval.approval-history-table modelName='{{ get_class($tax_return) }}'
                        modelId="{{ encrypt($tax_return->id) }}" />
                </div>
            </div>
        </div>
        <div class="tab-pane fade card p-2" id="history" role="tabpanel" aria-labelledby="history-tab">
            @include('vetting.includes.return-histories')
        </div>
    </div>

@endsection
