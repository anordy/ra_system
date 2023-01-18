@extends('layouts.master')

@section('title', 'Daily Receipts')

@section('content')
<div class="card rounded-0">
    <div>
        <div class="card-body">
            <div class="row mt-3 mx-2">
                <div class="col-md-12">
                    <table class="table table-condensed table-bordered">
                        <thead class="border-bottom border-dark">
                            <tr>
                                <th colspan="4" class="text-center bg-secondary">
                                    @if ($data['range_start'] == $data['today'])
                                    <span class="text-success">{{ $data['tax_type']->name }} Today's Collections From ({{
                                        date('d-M-Y',strtotime($data['today']))}})</span>
                                    @else
                                    {{ $data['tax_type']->name }} Collections From
                                    <span class="text-primary"> {{ date('d-M-Y',strtotime($data['range_start'])) }}
                                    </span>
                                    to
                                    <span class="text-primary"> {{ date('d-M-Y',strtotime($data['range_end'])) }}
                                    </span>
                                    @endif
                                </th>
                            </tr>
                            <tr class="text-center">
                                <th class="text-left">Source</th>
                                <th>Shilings</th>
                                <th>Dollars</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="border-bottom border-dark text-right">
                            @foreach ($data['payments'] as $row)
                            <tr>
                                <td class="text-left">{{ $data['tax_type']->name ?? '' }}</td>
                                <td>{{ number_format($row->currency == 'TZS' ? $row->paid_amount : 0,2) }}</td>
                                <td>{{ number_format($row->currency == 'USD' ? $row->paid_amount : 0,2) }}</td>
                                <td class="text-center"><a class="btn btn-outline-success"
                                        href="{{ route('payments.show', encrypt($row->id)) }}">Expore</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="text-right">
                            <tr>
                                <th class="text-left">Total</th>
                                <th>{{ number_format($data['totalTzs'],2) }}</th>
                                <th>{{ number_format($data['totalUsd'],2) }}</th>
                                <th class="bg-secondary"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection