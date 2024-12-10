@extends('layouts.master')

@section('title','Report Register Settings')

@section('content')

    @can('report-register-alter-settings')
        @livewire('report-register.settings.base-settings')
    @endcan

    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Taxpayer Incidents Categories</h5>
            <div class="card-tools">
                @can('report-register-alter-settings')
                    <button class="btn btn-primary btn-sm px-3"
                            onclick="Livewire.emit('showModal', 'report-register.settings.category.create-category', '{{ encrypt(\App\Enum\ReportRegister\RgRequestorType::TAXPAYER) }}')">
                        <i class="bi bi-plus-circle-fill pr-2"></i>
                        Add New Category
                    </button>
                @endcan
            </div>
        </div>
        <div class="card-body">
            @livewire('report-register.settings.category.category-table', ['requesterType' => \App\Enum\ReportRegister\RgRequestorType::TAXPAYER])
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Staff Incidents Categories</h5>
            <div class="card-tools">
                @can('report-register-alter-settings')
                    <button class="btn btn-primary btn-sm px-3"
                            onclick="Livewire.emit('showModal', 'report-register.settings.category.create-category', '{{ encrypt(\App\Enum\ReportRegister\RgRequestorType::STAFF)  }}')">
                        <i class="bi bi-plus-circle-fill pr-2"></i>
                        Add New Category
                    </button>
                @endcan
            </div>
        </div>
        <div class="card-body">
            @livewire('report-register.settings.category.category-table', ['requesterType' => \App\Enum\ReportRegister\RgRequestorType::STAFF])
        </div>
    </div>
@endsection
