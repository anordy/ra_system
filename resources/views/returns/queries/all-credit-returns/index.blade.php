@extends('layouts.master')

@section('css')
    <link href="{{ asset('plugins/datatables/datatables.min.css') }}" rel="stylesheet" />
@endsection

@section('title','Return Queries')

@section('content')
    <div class="card">
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#hotel_levy" class="nav-item nav-link font-weight-bold active">Hotel Levy</a>
                <a href="#stamp_duty" class="nav-item nav-link font-weight-bold">Stamp Duty</a>
                <a href="#vat" class="nav-item nav-link font-weight-bold">Value Added Tax (VAT)</a>
            </nav>
            <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                <div id="hotel_levy" class="tab-pane fade active show  p-2">
                    <div class="card">
                        <div class="card-header">All Credit Returns for Hotel Levy</div>
                        <div class="card-body">
                            <table  class="table table-bordered myTable">
                                <thead>
                                <tr>
                                    <th>No:</th>
                                    <th>Tax Payer</th>
                                    <th>Business Name</th>
                                    <th>Business Location</th>
                                    <th>Sales</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if(!empty($hotel_returns))
                                    @foreach($hotel_returns as $index=>$return)
                                        @if($return->total_sales == 0)
                                            <tr>

                                                <td>{{$index + 1}}</td>
                                                <td>{{$return->business->taxpayer->first_name ?? 'N/A'}} {{$return->business->taxpayer->last_name ?? 'N/A'}}</td>
                                                <td>{{$return->business->name ?? 'N/A'}}</td>
                                                <td>{{$return->businessLocation->name ?? 'N/A'}}</td>
                                                <td>{{number_format($return->total_sales,2)}}
                                                    <strong>
                                                        {{ $return->currency ?? 'N/A'  }}
                                                    </strong>
                                                </td>
                                                <td>
                                                    <a href="{{route('queries.all-credit-returns.show', [encrypt($return->id), encrypt($return->tax_type_id), encrypt($return->total_sales)])}}" class="btn btn-info btn-sm" data-toggle="tooltip"
                                                       data-placement="right" title="View">
                                                        <i class="bi bi-eye-fill"></i>
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="stamp_duty" class="tab-pane fade  p-2">
                    <div class="card">
                        <div class="card-header">All Credit Returns for Stamp Duty</div>
                        <div class="card-body">
                            <table  class="table table-bordered myTable">
                                <thead>
                                <tr>
                                    <th>No:</th>
                                    <th>Tax Payer</th>
                                    <th>Business Name</th>
                                    <th>Business Location</th>
                                    <th>Sales</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($stamp_duty_returns))
                                    @foreach($stamp_duty_returns as $index=>$return)
                                        @if($return->total_sales == 0)
                                            <tr>
                                                <td>{{$index + 1}}</td>
                                                <td>{{$return->business->taxpayer->first_name ?? 'N/A'}} {{$return->business->taxpayer->last_name ?? 'N/A'}}</td>
                                                <td>{{$return->business->name ?? 'N/A'}}</td>
                                                <td>{{$return->businessLocation->name ?? 'N/A'}}</td>
                                                <td>{{number_format($return->total_sales,2)}}
                                                    <strong>
                                                        {{ $return->currency ?? 'N/A'  }}
                                                    </strong>
                                                </td>
                                                <td>
                                                    <a href="{{route('queries.all-credit-returns.show', [encrypt($return->id), encrypt($return->tax_type_id), encrypt($return->total_sales)])}}" class="btn btn-info btn-sm" data-toggle="tooltip"
                                                       data-placement="right" title="View">
                                                        <i class="bi bi-eye-fill"></i>
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="vat" class="tab-pane fade  p-2">
                    <div class="card">
                        <div class="card-header">All Credit Returns for Value Added Tax (VAT)</div>
                        <div class="card-body">
                            <table class="table table-bordered myTable">
                                <thead>
                                <tr>
                                    <th>No:</th>
                                    <th>Tax Payer</th>
                                    <th>Business Name</th>
                                    <th>Business Location</th>
                                    <th>Sales</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($vat_returns))
                                    @foreach($vat_returns as $index=>$return)
                                    @if($return->total_sales == 0)
                                        <tr>
                                            <td>{{$index + 1}}</td>
                                            <td>{{$return->business->taxpayer->first_name ?? 'N/A'}} {{$return->business->taxpayer->last_name ?? 'N/A'}}</td>
                                            <td>{{$return->business->name ?? 'N/A'}}</td>
                                            <td>{{$return->businessLocation->name ?? 'N/A'}}</td>
                                            <td>{{number_format($return->total_sales,2)}}
                                                <strong>
                                                    {{ $return->currency ?? 'N/A'  }}
                                                </strong>
                                            </td>
                                            <td>
                                                <a href="{{route('queries.all-credit-returns.show', [encrypt($return->id), encrypt($return->tax_type_id), encrypt($return->total_sales)])}}" class="btn btn-info btn-sm" data-toggle="tooltip"
                                                   data-placement="right" title="View">
                                                    <i class="bi bi-eye-fill"></i>
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('plugins/datatables/datatables.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $(".nav-tabs a").click(function() {
                $(this).tab('show');
            });
        });

        $(document).ready( function () {
            $('.myTable').DataTable();
        } );
    </script>

@endsection