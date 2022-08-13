@extends('layouts.master')

@section('title', "Lump Sum Payments - {$location->business->name}")

@section('content')
    <div class="card rounded-0">
        <div class="card-body">

            @livewire('returns.lump-sum.lump-sum-returns-add', ['location' => $location, 'tax_type' => $tax_type, 'business' => $business, 'filling_month_id' => $filling_month_id])

        </div>

    </div>
@endsection
