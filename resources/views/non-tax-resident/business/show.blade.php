@extends('layouts.master')

@section('title', 'Business Registration Details')

@section('content')

    <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist" style="margin-bottom: 0;">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
               aria-selected="true">Business Information</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="home-tab" data-toggle="tab" href="#returns" role="tab" aria-controls="returns"
               aria-selected="true">Tax Returns</a>
        </li>
    </ul>

    <div class="tab-content bg-white border shadow-sm" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row m-2 pt-3">
                <div class="col-md-3 mb-3">
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
                                De-registered
                            </span>
                        @else
                            <span class="font-weight-bold text-info">
                                <i class="bi bi-clock-history mr-1"></i>
                                Unknown Status
                            </span>
                        @endif
                    </p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Name</span>
                    <p class="my-1">{{ $business->name }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">ZTN Number</span>
                    <p class="my-1">{{ $business->ztn_number }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">VRN Number</span>
                    <p class="my-1">{{ $business->vrn }}</p>
                </div>
                @if($business->category)
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Business Category</span>
                        <p class="my-1">{{ $business->category->name ?? 'N/A' }}</p>
                    </div>
                @endif
                @if($business->other_category)
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Business Category</span>
                        <p class="my-1">{{ $business->other_category }}</p>
                    </div>
                @endif
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Email</span>
                    <p class="my-1">{{ $business->email }}</p>
                </div>
                @if($business->business_address)
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Business Address</span>
                        <p class="my-1">{{ $business->business_address }}</p>
                    </div>
                @endif
                @if($business->nature)
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Nature of Business</span>
                        <p class="my-1">{{ $business->nature->name ?? 'N/A' }}</p>
                    </div>
                @endif
                @if($business->other_nature_of_business)
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Nature of Business</span>
                        <p class="my-1">{{ $business->other_nature_of_business }}</p>
                    </div>
                @endif
                @if($business->country)
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Business Country</span>
                        <p class="my-1">{{ $business->country->name ?? 'N/A' }}</p>
                    </div>
                @endif
                @if($business->street)
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Business Street</span>
                        <p class="my-1">{{ $business->street }}</p>
                    </div>
                @endif
                @if($business->annual_revenue_threshold)
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Annual Revenue Threshold (USD)</span>
                        <p class="my-1">{{ $business->annual_revenue_threshold }}</p>
                    </div>
                @endif
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Registration Date</span>
                    <p class="my-1">{{ $business->created_at ? \Carbon\Carbon::create($business->created_at)->format('d M, Y') : 'N/A' }}</p>
                </div>
            </div>

            @if($business->owner)
                <h6 class="mx-4">Owner's Information</h6>
                <div class="row m-2 pt-3">
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Full Name</span>
                        <p class="my-1">{{ $business->owner->full_name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Position</span>
                        <p class="my-1">{{ $business->owner->position ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Nationality</span>
                        <p class="my-1">{{ $business->owner->country->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Address</span>
                        <p class="my-1">{{ $business->owner->address ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Passport No</span>
                        <p class="my-1">{{ $business->owner->passport_number ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Identification No</span>
                        <p class="my-1">{{ $business->owner->id_number ?? 'N/A' }}</p>
                    </div>
                </div>
            @endif


            @if(count($business->socials ?? []))
                <h6 class="mx-4">Social Media Accounts</h6>
                <hr>
                <div class="row m-2 pt-3">
                    @foreach($business->socials as $social)
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase"><i
                                        class="mr-2 bi bi-{{$social->account->icon ?? ''}}"></i>{{ $social->account->name ?? 'N/A' }}</span>
                            <p class="my-1"><a class="text-primary" href="{{ $social->url }}"
                                               target="_blank">{{ $social->url }}</a></p>
                        </div>
                    @endforeach
                </div>
            @endif

            @if(count($business->socials ?? []))
                <h6 class="mx-4">Contact Persons</h6>
                <hr>
                <div class="row m-2 pt-3">
                    @foreach($business->contacts as $contact)
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Name: {{ $contact->name ?? 'N/A' }}</span>
                            <p class="my-1">Tel: {{ $contact->phone ?? 'N/A' }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            @if(count($business->attachments ?? []))
                <h6 class="mx-4">Attachments</h6>
                <hr>
                <div class="row m-2 pt-3">
                    @foreach($business->attachments as $attachment)
                        <div class="col-md-3 mb-3">
                            <div
                                    class="p-2 mb-3 d-flex rounded-sm align-items-center file-background">
                                <i class="bi bi-file-earmark-pdf-fill px-2 font-x-large"></i>
                                <a target="_blank"
                                   href="{{ route('tax-return-cancellation.file', encrypt($attachment->attachment_path)) }}"
                                   class="ml-1 font-weight-bold">
                                    {{ $attachment->type->name ?? 'N/A' }}
                                    <i class="bi bi-arrow-up-right-square ml-1"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="tab-pane fade show" id="returns" role="tabpanel" aria-labelledby="returns-tab">
            <div class="m-4">
                <livewire:non-tax-resident.returns.business-returns-table ntrBusinessId="{{ encrypt($business->id) }}"/>
            </div>
        </div>
    </div>

@endsection