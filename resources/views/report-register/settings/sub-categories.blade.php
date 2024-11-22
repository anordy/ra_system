@extends('layouts.master')

@section('title','Report Register Settings')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Report Register Settings - Sub Categories Configuration</h5>
            <div class="card-tools">
                @can('report-register-alter-settings')
                    <button class="btn btn-primary btn-sm px-3"
                            onclick="Livewire.emit('showModal', 'report-register.settings.sub-category.create-sub-category', '{{ $id }}')">
                        <i class="bi bi-plus-circle-fill pr-2"></i>
                        Add New Sub Category
                    </button>
                @endcan
            </div>
        </div>
        <div class="card-body">
            @livewire('report-register.settings.sub-category.sub-category-table', ['categoryId' => $id])
        </div>
    </div>
@endsection
