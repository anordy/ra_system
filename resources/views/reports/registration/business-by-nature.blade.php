@extends('layouts.master')

@section('title','Managerial Reports')

@section('content')
<div class="card">
    <div class="card-header text-uppercase font-weight-bold">
        Business Registration By Nature (ISIC LEVEL {{ $level }}) Reports
    </div>
    <div class="card-body mt-0">
        @livewire('reports.registration.previews.business.business-nature-preview-table',['isicId'=>$isicId,'level'=>$level])
    </div>
</div>
@endsection
