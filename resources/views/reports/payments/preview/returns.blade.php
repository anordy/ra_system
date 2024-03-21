@extends('layouts.master')

@section('title','Report preview')

@section('content')
<div class="d-flex justify-content-start mb-3">
    <a href="{{ route('reports.returns') }}" class="btn btn-info">
        <i class="fas fa-arrow-left"></i>
        Back
    </a>
</div>
<div class="card">
    <div class="card-header text-uppercase font-weight-bold">
        {{ $parameters['type']=='Filing' ? $parameters['filing_report_type'] : $parameters['payment_report_type'] }} Report preview for {{ $parameters['tax_type_name'] }} Returns 
        @if ($parameters['dates']['startDate'])
            From <span class="text-primary">{{date("M, d Y", strtotime($parameters['dates']['from'])) }}</span> To <span class="text-primary">{{ date("M, d Y", strtotime($parameters['dates']['to']))}}</span>
        @endif
    </div>
    <div class="card-body mt-0">
        <table class="table table-bordered table-striped normal-text">
            <thead>
            <th>Business Name</th>
            <th>Location Name</th>
            <th>Filed By</th>
            <th>Principal Amount</th>
            <th>Interest</th>
            <th>Penalty</th>
            <th>Infrastructure</th>
            <th>Total Amount</th>
            <th>outstanding_amount</th>
            <th>Currency</th>
            <th>Payment Status</th>
            </thead>
            <tbody>
            @foreach ($local_purchases_data as $item)
                <tr>
                    <td>{{ $item->business_id }}</td>
                    <td>{{ $item->location_id }}</td>
                    <td>{{ $item->filed_by_id }}</td>
                    <td>{{ $item->principal }}</td>
                    <td>{{ $item->interest }}</td>
                    <td>{{ $item->penalty }}</td>
                    <td>{{ $item->infrastructure }}</td>
                    <td>{{ $item->total_amount }}</td>
                    <td>{{ $item->outstanding_amount }}</td>
                    <td>{{ $item->currency }}</td>
                    <td>{{ $item->payment_status }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
