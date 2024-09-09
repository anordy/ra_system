@extends('layouts.master')

@section('title','Report Register Settings')

@section('content')

    @livewire('report-register.settings.base-settings')


    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Report Register Incident Categories</h5>
            <div class="card-tools">
                <button class="btn btn-primary btn-sm px-3"
                        onclick="Livewire.emit('showModal', 'report-register.settings.category.create-category')">
                    <i class="bi bi-plus-circle-fill pr-2"></i>
                    Add New Category
                </button>
            </div>
        </div>
        <div class="card-body">
            @livewire('report-register.settings.category.category-table')
        </div>
    </div>
@endsection
