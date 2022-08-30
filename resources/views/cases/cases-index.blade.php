@extends('layouts.master')

@section('title', 'Legal Cases Management')

@section('content')
    <div class="card mt-3">
        <div class="card-header bg-white">
            <h5>Cases</h5>
            <div class="card-tools">
                <button class="btn btn-info btn-sm"
                        onclick="Livewire.emit('showModal', 'cases.register-case',)"><i
                            class="fa fa-plus-circle"></i>
                    New Case</button>
            </div>
        </div>

        <div class="card-body">
            <livewire:cases.cases-table />
        </div>
    </div>
@endsection

