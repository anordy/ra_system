@extends('layouts.master')
@section('title', 'Waiver Or Reduction')

@section('content')
    <div class="card">
        <div class="card-body">
            <livewire:assesments.waiver.wizard waiver_id="{{ $waiverId ?? ''}}"  location_id="{{ $location_id ?? '' }}"
                tax_type_id="{{ $tax_type_id ?? '' }}">
            </livewire:assesments.waiver.wizard>
        </div>
    </div>
@endsection
