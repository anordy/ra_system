@extends('layouts.master')

@section('title', 'Verifications')

@section('content')
    <div class="card">
        <div class="card-header">
            <h6 class="text-uppercase">Verifications</h6>
            <div class="card-tools">
                <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'user-add-modal')"><i
                        class="fa fa-plus-circle"></i>
                    Add</button>
            </div>
        </div>

        <div class="card-body">
            @livewire('verification.verification-table')
        </div>
    </div>
@endsection
