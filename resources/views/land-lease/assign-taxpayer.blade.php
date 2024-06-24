@extends('layouts.master')

@section('title', 'Assign Taxpayer To Land Lease')

@section('content')
    <div class="d-flex justify-content-start mb-3">
        <a href="{{ route('land-lease.list') }}" class="btn btn-info">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back') }}
        </a>
    </div>
    @livewire('land-lease.assign-taxpayer', ['enc_id' => $id])
@endsection
