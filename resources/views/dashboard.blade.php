@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-blue order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Staff</h6>
                    <h2 class="text-right"><i class="bi bi-person-badge f-left"></i><span>{{ \App\Models\User::count() }}</span></h2>
                    <p class="mb-0">Administrators<span class="f-right">351</span></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-green order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Taxpayers</h6>
                    <h2 class="text-right"><i class="bi bi-person-check-fill f-left"></i><span>{{ \App\Models\Taxpayer::count() }}</span></h2>
                    <p class="mb-0">KYC<span class="f-right">{{ \App\Models\KYC::count() }}</span></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-yellow order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Registered Businesses</h6>
                    <h2 class="text-right"><i class="bi bi-building f-left"></i><span>{{ \App\Models\Business::approved()->count() }}</span></h2>
                    <p class="mb-0">Pending Approval<span class="f-right">351</span></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-pink order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Tax Agents</h6>
                    <h2 class="text-right"><i class="bi bi-credit-card f-left"></i><span>{{ \App\Models\TaxAgent::count() }}</span></h2>
                    <p class="mb-0">Pending Agents<span class="f-right">{{ \App\Models\TaxAgent::pending()->count() }}</span></p>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection