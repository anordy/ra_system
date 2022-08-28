@extends('layouts.master')

@section('title','Managerial Reports')

@section('content')
<div class="card">
    <div class="card-header text-uppercase font-weight-bold">
        Business Registration By Tax type Reports
    </div>
    <div class="card-body mt-0">
        @livewire('reports.registration.previews.business.business-tax-type-preview-table',['tax_type_id'=>$tax_type_id])
    </div>
</div>
@endsection
