@extends('layouts.master')
@section('css')
    <link href="{{ asset('plugins/datatables/datatables.min.css') }}" rel="stylesheet"/>
@endsection
@section('title','Return Queries')

@section('content')
    <div class="card">
        <div class="card-header">
            Returns details
        </div>
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#result_one" class="nav-item nav-link font-weight-bold active">Difference between sales and
                    purchases</a>
                <a href="#result_two" class="nav-item nav-link font-weight-bold">Purchases exceed 1/3 of the Sales</a>
            </nav>
            <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                <div id="result_one" class="tab-pane fade active show  p-2">
                    <div class="card">
                        <div class="card-header">Returns for difference between sales and purchases less or equal to
                            10%
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered myTable">
                                <thead>
                                <tr>
                                    <th>No:</th>
                                    <th>Tax Payer</th>
                                    <th>Business Name</th>
                                    <th>Business Location</th>
                                    <th>Tax Type</th>
                                    <th>Sales</th>
                                    <th>Purchases</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if(!empty($returns))
                                    @foreach($returns as $index=>$return)
                                        @if($return->category == 'less than 10 percentage')
                                            <tr>
                                                <td>{{$index + 1}}</td>
                                                <td>{{$return->business->taxpayer->first_name ?? 'N/A'}} {{$return->business->taxpayer->last_name ?? 'N/A'}}</td>
                                                <td>{{$return->business->name ?? 'N/A'}}</td>
                                                <td>{{$return->businessLocation->name ?? 'N/A'}}</td>
                                                <td>{{$return->taxType->name ?? 'N/A'}}</td>
                                                <td>{{number_format($return['total_sales'],2)}}
                                                    <strong>
                                                        {{ $return->currency ?? 'N/A'  }}
                                                    </strong>
                                                </td>
                                                <td>{{number_format($return['total_purchases'],2)}}
                                                    <strong>
                                                        {{ $return->currency ?? 'N/A'  }}
                                                    </strong>
                                                </td>
                                                <td>
                                                    <a href="{{route('queries.sales-purchases.show',[encrypt($return->id)])}}" class="btn btn-info btn-sm" data-toggle="tooltip"
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
                <div id="result_two" class="tab-pane fade  p-2">
                    <div class="card">
                        <div class="card-header">Returns whose purchases exceed 1/3 of the Sales</div>
                        <div class="card-body">
                            <table class="table table-bordered myTable">
                                <thead>
                                <tr>
                                    <th>No:</th>
                                    <th>Tax Payer</th>
                                    <th>Business Name</th>
                                    <th>Business Location</th>
                                    <th>Tax Type</th>
                                    <th>Sales</th>
                                    <th>Purchases</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($returns))
                                    @foreach($returns as $index=>$return)
                                        @if($return->category == 'one third of sales')
                                            <tr>
                                                <td>{{$index + 1}}</td>
                                                <td>{{$return->business->taxpayer->first_name ?? 'N/A'}} {{$return->business->taxpayer->last_name ?? 'N/A'}}</td>
                                                <td>{{$return->business->name ?? 'N/A'}}</td>
                                                <td>{{$return->businessLocation->name ?? 'N/A'}}</td>
                                                <td>{{$return->taxType->name ?? 'N/A'}}</td>
                                                <td>{{number_format($return['total_sales'],2)}}
                                                    <strong>
                                                        {{ $return->currency ?? 'N/A'  }}
                                                    </strong>
                                                </td>
                                                <td>{{number_format($return['total_purchases'],2)}}
                                                    <strong>
                                                        {{ $return->currency ?? 'N/A'  }}
                                                    </strong>
                                                </td>
                                                <td>
                                                    <a href="{{route('queries.sales-purchases.show',[encrypt($return->id)])}}" class="btn btn-info btn-sm" data-toggle="tooltip"
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
        $(document).ready(function () {
            $(".nav-tabs a").click(function () {
                $(this).tab('show');
            });
        });
        $(document).ready(function () {
            $('.myTable').DataTable();
        });
    </script>

@endsection