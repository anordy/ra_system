@extends('layouts.master')

@section('title', 'Audits Debt Management')

@section('content')
<div class="card p-0 m-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="text-uppercase font-weight-bold">Investigation Debts</div>
        <div class="card-tools">

        </div>
    </div>
    <div class="card-body mt-0 p-2">
        <table class="table table-md">
            <thead>
                <tr>
                    <th>Business Name</th>
                    <th>Location</th>
                    <th>Assessment Type</th>
                    <th>Principal</th>
                    <th>Penalty</th>
                    <th>Interest</th>
                    <th>Total Debt</th>
                </tr>
            </thead>
            <tbody>
                @if (count($assessmentDebts))
                    @foreach ($assessmentDebts as $assessment)
                        <tr>
                            <td>{{ $assessment->business->name }}</td>
                            <td>{{ $assessment->location->name }}</td>
                            <td>{{ preg_split('[\\\]', $assessment->assessment_type)[2] }}</td>
                            <td>{{ number_format($assessment->principal_amount, 2) }}</td>
                            <td>{{ number_format($assessment->penalty_amount, 2) }}</td>
                            <td>{{ number_format($assessment->interest_amount, 2) }}</td>
                            <td>{{ number_format($assessment->principal_amount + $assessment->penalty_amount + $assessment->interest_amount, 2) }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center py-3">
                            No assessment debts.
                        </td>
                    </tr>
                @endif

            </tbody>
        </table>
    </div>
</div>
@endsection
