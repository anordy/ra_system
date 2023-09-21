@extends('layouts.master')

@section('title', 'Internal Business Information Change')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            <div class="d-flex justify-content-between align-items-center bg-white">
                <div class="text-uppercase font-weight-bold">Internal Business Information Change
                </div>
                @if (auth()->user->role->name === 'Registration Manager')
                <div class="card-tools">
                    <button class="btn btn-primary btn-sm px-3" onclick="Livewire.emit('showModal', 'internal-info-change.initiate-change-modal')">
                        <i class="bi bi-plus-circle-fill pr-2"></i>Initiate Change
                    </button>
                </div>  
                @endif
            </div>
        </div>

        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#all" class="nav-item nav-link font-weight-bold active">Approved Changes</a>
                <a href="#pending-approval" class="nav-item nav-link font-weight-bold">Pending Approval</a>
                <a href="#approval-progress" class="nav-item nav-link font-weight-bold">Approval Progress</a>
            </nav>
          
            <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                <div id="all" class="tab-pane fade active show p-2">
                    @livewire('internal-info-change.internal-info-change-table')
                </div>
                <div id="pending-approval" class="tab-pane fade p-2">
                    @livewire('internal-info-change.internal-info-change-approval-table')
                </div>
                <div id="approval-progress" class="tab-pane fade p-2">
                    @livewire('internal-info-change.internal-info-change-approval-progress-table')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".nav-tabs a").click(function() {
                $(this).tab('show');
            });
        });
    </script>
@endsection
