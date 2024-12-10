@extends('layouts.master')

@section('title','View Registered Report')

@section('content')
    @livewire('report-register.incident.view-incident', ['incidentId' => $id])
@endsection