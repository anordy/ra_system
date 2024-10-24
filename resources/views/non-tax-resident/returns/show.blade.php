@extends('layouts.master')

@section('title', 'Business Registration Details')

@section('content')
    <div class="d-flex justify-content-end pb-2">
        <a class="btn btn-info" href="{{route('ntr.business.index')}}">
            <i class="bi bi-arrow-return-left mr-2"></i>
            Back
        </a>
    </div>

    <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist" style="margin-bottom: 0;">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
               aria-selected="true">Business Information</a>
        </li>
    </ul>

    <div class="tab-content bg-white border shadow-sm" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">{{ __('Business Status') }}</span>
                    <p class="my-1">
                        @if($business->status === \App\Models\BusinessStatus::APPROVED)
                            <span class="font-weight-bold text-success">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                Approved
                            </span>
                        @elseif($business->status === \App\Models\BusinessStatus::DEREGISTERED)
                            <span class="font-weight-bold text-danger">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                Rejected
                            </span>
                        @else
                            <span class="font-weight-bold text-info">
                                <i class="bi bi-clock-history mr-1"></i>
                                Unknown Status
                            </span>
                        @endif
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Name</span>
                    <p class="my-1">{{ $business->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">ZTN Number</span>
                    <p class="my-1">{{ $business->ztn_location_number }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">VRN Number</span>
                    <p class="my-1">{{ $business->vrn }}</p>
                </div>
                @if($business->category)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Business Category</span>
                        <p class="my-1">{{ $business->category->name ?? 'N/A' }}</p>
                    </div>
                @endif
                @if($business->other_category)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Business Category</span>
                        <p class="my-1">{{ $business->other_category }}</p>
                    </div>
                @endif
                @if($business->individual_position)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Individual Position</span>
                        <p class="my-1">{{ $business->individual_position }}</p>
                    </div>
                @endif
                @if($business->individual_address)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Individual Address</span>
                        <p class="my-1">{{ $business->individual_address }}</p>
                    </div>
                @endif
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Email</span>
                    <p class="my-1">{{ $business->email }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Address</span>
                    <p class="my-1">{{ $business->business_address }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Nature of Business</span>
                    <p class="my-1">{{ $business->nature_of_business }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Country</span>
                    <p class="my-1">{{ $business->country->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Street</span>
                    <p class="my-1">{{ $business->street }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Registered By</span>
                    <p class="my-1">{{ $business->taxpayer->full_name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Registration Date</span>
                    <p class="my-1">{{ $business->created_at ? \Carbon\Carbon::create($business->created_at)->format('d M, Y') : 'N/A' }}</p>
                </div>

            </div>

            <h6 class="mx-4">Social Media Accounts</h6><hr>
            <div class="row m-2 pt-3">
                @foreach($business->socials ?? [] as $social)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">{{ $social->account->name ?? 'N/A' }}</span>
                        <p class="my-1"><a class="text-primary" href="{{ $social->url }}" target="_blank">{{ $social->url }}</a></p>
                    </div>
                @endforeach
            </div>

            <h6 class="mx-4">Contact Persons</h6><hr>
            <div class="row m-2 pt-3">
                @foreach($business->contacts ?? [] as $contact)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">{{ $contact->name ?? 'N/A' }}</span>
                        <p class="my-1">Tel: {{ $contact->phone ?? 'N/A' }}</p>
                    </div>
                @endforeach
            </div>

            <h6 class="mx-4">Attachments</h6><hr>
            <div class="row m-2 pt-3">
                @foreach($business->attachments ?? [] as $attachment)
                    <div class="col-md-4 mb-3">
                        <div
                             class="p-2 mb-3 d-flex rounded-sm align-items-center file-background">
                            <i class="bi bi-file-earmark-pdf-fill px-2 font-x-large"></i>
                            <a target="_blank" href="{{ route('tax-return-cancellation.file', encrypt($attachment->attachment_path)) }}"
                               class="ml-1 font-weight-bold">
                                {{ $attachment->type->name ?? 'N/A' }}
                                <i class="bi bi-arrow-up-right-square ml-1"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection