@extends('layouts.master')

@section('title','Managerial Reports')

@section('content')
<div class="card">
    <div class="card-header text-uppercase font-weight-bold">
        Business Registered under {{ $taxType->name }} Tax type 
    </div>
    <div class="card-body mt-0">
        @livewire('reports.registration.previews.business.business-tax-type-preview-table',['tax_type_id'=>$taxType->id])
    </div>
</div>
@endsection
