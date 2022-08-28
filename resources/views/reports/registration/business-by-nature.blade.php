@extends('layouts.master')

@section('title','Managerial Reports')

@section('content')
<div class="card">
    <div class="card-header text-uppercase font-weight-bold">
        Business Registration By Nature (ISIC I) Reports
    </div>
    <div class="card-body mt-0">
        @livewire('reports.registration.previews.business.business-nature-preview-table',['isic1Id'=>$isic1Id])
    </div>
</div>
@endsection
