@extends('layouts.master')

@section('title')
    Change of Tax Type Request
@endsection

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            Change of Tax Type Request
        </div>
        <div class="card-body mt-0 p-2">
            {{-- <livewire:approval.branches-approval-processing modelName='App\Models\BusinessLocation' modelId="{{ $location->id }}" /> --}}

            <div class="row">
                <div class="row m-2">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Business Name</span>
                        <p class="my-1">{{ }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Tax Type</span>
                        <p class="my-1">{{ }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Currency</span>
                        <p class="my-1">{{ }}</p>
                    </div>
                </div>
                <hr style="margin-top: -16px" class="mx-3"/>
            </div>
        </div>
    </div>
@endsection