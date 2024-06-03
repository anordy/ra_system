@extends("layouts.master")

@section("title", "Assessment Payments")

@section("content")
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Assessement Payments
        </div>
        <div class="card-body">
            @livewire("investigation.tax-investigation-assessment-payments-table")
        </div>
    </div>
@endsection
