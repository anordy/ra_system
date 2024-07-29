@extends('layouts.master')

@section('title')
    System Certificate Management
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-white text-uppercase font-weight-bold">
            System Certificate Management
            <div class="card-tools">
                @can('system-setting-add')
                    @if (approvalLevel(Auth::user()->level_id, \App\Enum\GeneralConstant::MAKER))
                        <button class="btn btn-primary btn-sm"
                                onclick="Livewire.emit('showModal', 'settings.certificate-signature.certificate-signature-add-modal')"><i
                                    class="bi bi-plus-circle-fill pr-1"></i>
                            Add Certificate</button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('settings.certificate-signature.certificate-signature-table')
        </div>
    </div>
@endsection
