@extends('layouts.master')

@section('title')
    Business Closures
@endsection

@section('content')
    <nav class="nav nav-tabs mb-3">
        <a href="#tab1" class="nav-item nav-link font-weight-bold active">Pending Closures</a>
        <a href="#tab2" class="nav-item nav-link font-weight-bold">Approved Closures</a>
        <a href="#tab3" class="nav-item nav-link font-weight-bold">Rejected Closures</a>
    </nav>
  
    <div class="tab-content">
      <div id="tab1" class="tab-pane fade active show">
        @livewire('business.closure.pending-closures-table')
        </div>
      <div id="tab2" class="tab-pane fade">
        @livewire('business.closure.approved-closures-table')
       </div>
       <div id="tab3" class="tab-pane fade">
        @livewire('business.closure.rejected-closures-table')
       </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
      $(".nav-tabs a").click(function(){
        $(this).tab('show');
      });
    });
    </script>
@endsection