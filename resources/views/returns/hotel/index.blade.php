@extends('layouts.master')

@section('title','Hotel Returns')

@section('content')
<div class="card p-0 m-0 mb-3">
    <div class="card-header text-uppercase">
        Summary
    </div>
    <div class="card-body mt-0 p-2">
        @livewire('returns.return-summary',['vars'=>$vars])
    </div>
</div>

<div class="card p-0 m-0">
    <div class="card-header text-uppercase font-weight-bold">
        Hotel Returns
    </div>
    @livewire('returns.return-card-report', ['data' => $data])
    <div class="card-body mt-0 p-2">
        <livewire:returns.hotel.hotel-returns-table status='all'></livewire:returns.hotel.hotel-returns-table>
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
