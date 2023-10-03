@extends('layouts.master')

@section('title', 'TIN Details')

@section('content')
    <div class="card mt-3">
        <div class="card-header bg-white font-weight-bold">
            TIN Details
        </div>
        <div class="card-body">
            <div class="row my-2">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">TIN</span>
                    <p class="my-1">{{ $tin['tin'] }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">First Name</span>
                    <p class="my-1">{{ $tin['first_name'] }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Middle Name</span>
                    <p class="my-1">{{ $tin['middle_name'] ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Last Name</span>
                    <p class="my-1">{{ $tin['last_name'] ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Email</span>
                    <p class="my-1">{{ $tin['email'] ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Mobile</span>
                    <p class="my-1">{{ $tin['mobile'] ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Gender</span>
                    <p class="my-1">{{ $tin['gender'] }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Date of Birth</span>
                    <p class="my-1">{{ $tin['date_of_birth'] ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Nationality</span>
                    <p class="my-1">{{ $tin['nationality'] ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Taxpayer Name</span>
                    <p class="my-1">{{ $tin['taxpayer_name'] ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Trading Name</span>
                    <p class="my-1">{{ $tin['trading_name'] ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">District</span>
                    <p class="my-1">{{ $tin['district'] }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Region</span>
                    <p class="my-1">{{ $tin['region'] }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Street</span>
                    <p class="my-1">{{ $tin['street'] }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Postal City</span>
                    <p class="my-1">{{ $tin['postal_city'] ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Plot Number</span>
                    <p class="my-1">{{ $tin['plot_number'] ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Block Number</span>
                    <p class="my-1">{{ $tin['block_number'] ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Vat Registration Number</span>
                    <p class="my-1">{{ $tin['vat_registration_number'] ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Status</span>
                    <p class="my-1">{{ $tin['status'] ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Is Business TIN</span>
                    <p class="my-1">{{ $tin['is_business_tin'] == 1 ? 'Yes' : 'No' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Is Entity TIN</span>
                    <p class="my-1">{{ $tin['is_entity_tin'] == 1 ? 'Yes' : 'No' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
