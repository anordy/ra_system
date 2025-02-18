@extends('layouts.master')

@section('title', 'Home')

@section('content')
    {{-- <div class="row">
        <div class="col-md-3">
            <div class="card bg-c-blue order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Staff</h6>
                    <h2 class="text-right"><i
                            class="bi bi-person-badge f-left"></i><span>{{ $counts['users'] }}</span></h2>
                    <p class="mb-0"><a href="{{ route('settings.users.index') }}">click here to view more</a></p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-c-green order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Taxpayers</h6>
                    <h2 class="text-right"><i
                            class="bi bi-person-check-fill f-left"></i><span>{{ $counts['taxpayers'] }}</span></h2>
                    <p class="mb-0"><a href="{{ route('taxpayers.taxpayer.index') }}">click here to view more</a></p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-c-yellow order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Businesses</h6>
                    <h2 class="text-right"><i
                            class="bi bi-building f-left"></i><span>{{ $counts['businesses'] }}</span>
                    </h2>
                    <p class="mb-0"><a href="{{ route('business.registrations.index') }}">click here to view more</a></p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-c-pink order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Tax Agents</h6>
                    <h2 class="text-right"><i
                            class="bi bi-credit-card f-left"></i><span>{{ $counts['taxAgents'] }}</span>
                    </h2>
                    <p class="mb-0"><a href="{{ route('taxagents.active') }}">click here to view more</a></p>
                </div>
            </div>
        </div>
    </div> --}}

  <p>Revenue Assurance</p>

@endsection
