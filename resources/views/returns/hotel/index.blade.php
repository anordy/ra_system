@extends('layouts.master')

@section('title','Hotel Returns History')

@section('content')
<div class="card p-0 m-0">
    <div class="card-header text-uppercase font-weight-bold">
        Hotel Returns
    </div>
    <div class="card-body mt-0 p-2">
        <nav class="nav nav-tabs mt-0 border-top-0">
            <a href="#tab1" class="nav-item nav-link font-weight-bold active">All Hotel Returns</a>
            <a href="#tab2" class="nav-item nav-link font-weight-bold">Requests</a>
        </nav>
        <div class="tab-content px-2 card pt-3 pb-2">
            <div id="tab1" class="tab-pane fade active show">
                <livewire:returns.hotel.hotel-returns-table status='all'></livewire:returns.hotel.hotel-returns-table>
            </div>
            <div id="tab2" class="tab-pane fade">
                <livewire:returns.hotel.hotel-returns-table status='submitted'></livewire:returns.hotel.hotel-returns-table>
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
