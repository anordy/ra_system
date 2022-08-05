@extends('layouts.master')
@section('title', 'Both Waiver & Objection')

@section('content')
    <div class="card">
        <div class="card-body">
            {{-- @livewire('assesments.waiver.wizard') --}}
            <livewire:assesments.waiverobjection.wizard waiver_objection_id="{{ $waiver_objection_id ?? ''}}"  location_id="{{ $location_id ?? '' }}"
                tax_type_id="{{ $tax_type_id ?? '' }}">
            </livewire:assesments.waiverobjection.wizard>
        </div>
    </div>
@endsection
