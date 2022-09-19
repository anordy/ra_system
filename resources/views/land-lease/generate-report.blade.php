@extends('layouts.master')

@section('title', 'General Land Lease Report')

@section('content')
    {{-- @livewire('land-lease.report-table', ['query' => "SELECT * FROM `zrb_system`.`land_leases` where created_at >= '2000/01/01 23:59:59' and created_at <= '2022/08/02 23:59:59'"]) --}}
    @livewire('land-lease.generate-report')
@endsection
