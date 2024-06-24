@extends('zrb-client.resources.views.layouts.master')

@section('title',__('Edit Chartered Return'))

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('returns.chartered.edit', ['return_id' => $return_id])
        </div>
    </div>
@endsection