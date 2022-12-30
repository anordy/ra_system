@extends('layouts.master')

@section('title')
    View User Audit Trail
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="text-uppercase font-weight-bold">User Audit Trail History</div>
        </div>
        <div class="card-body mt-0 p-2">
            <livewire:audit-trail.user-audit-logs userId="{{ $userId }}"></livewire:audit-trail.user-audit-logs>
        </div>
    </div>
@endsection

