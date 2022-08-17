@extends('layouts.master')

@section('title','Upgrade Tax Type')

@section('content')
    <div class="card">
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#hotel_levy" class="nav-item nav-link font-weight-bold active">Hotel Levy</a>
                <a href="#stamp_duty" class="nav-item nav-link font-weight-bold">Stamp Duty</a>
                <a href="#lump_sum" class="nav-item nav-link font-weight-bold">Lump Sum</a>
            </nav>
            <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                <div id="hotel_levy" class="tab-pane fade active show  p-2">
{{--                    <livewire:upgrade-tax-type.upgrade-hotel-table />--}}
                    <div class="card">
                        <div class="card-header">All business with hotel levy qualified to upgrade  their tax types</div>
                        <div class="card-body">
                            <table class="table table-bordered">
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

                                @foreach($returns as $index=>$return)
                                    @if($return->total_sales > 50000000)
                                    <tr>

                                        <td>{{$index + 1}}</td>
                                        <td>{{$return->business->taxpayer->first_name}} {{$return->business->taxpayer->last_name}}</td>
                                        <td>{{$return->business->name}}</td>
                                        <td>{{$return->businessLocation->name}}</td>
                                        <td>{{$return->total_sales}}</td>
                                        <td>
                                            <a href="{{route('upgrade-tax-types.show', encrypt($return->id))}}" class="btn btn-info btn-sm" data-toggle="tooltip"
                                               data-placement="right" title="View">
                                                <i class="bi bi-eye-fill"></i>
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="stamp_duty" class="tab-pane fade  p-2">
                    <div class="card">
                        <div class="card-header">All business with stamp duty qualified to upgrade  their tax types</div>
                        <div class="card-body">
                            <table class="table table-bordered">
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
                                @foreach($returns as $index=>$return)
                                    @if($return->total_sales > 50000000)
                                        <tr>
                                            <td>{{$index + 1}}</td>
                                            <td>{{$return->business->taxpayer->first_name}} {{$return->business->taxpayer->last_name}}</td>
                                            <td>{{$return->business->name}}</td>
                                            <td>{{$return->businessLocation->name}}</td>
                                            <td>{{$return->total_sales}}</td>
                                            <td>
                                                <a href="{{route('upgrade-tax-types.show', encrypt($return->id))}}" class="btn btn-info btn-sm" data-toggle="tooltip"
                                                   data-placement="right" title="View">
                                                    <i class="bi bi-eye-fill"></i>
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="lump_sum" class="tab-pane fade  p-2">
                    <div class="card">
                        <div class="card-header">All business with lump sum qualified to upgrade  their tax types</div>
                        <div class="card-body">
                            <table class="table table-bordered">
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
                                @foreach($returns as $index=>$return)
                                    @if($return->total_sales > 50000000)
                                        <tr>
                                            <td>{{$index + 1}}</td>
                                            <td>{{$return->business->taxpayer->first_name}} {{$return->business->taxpayer->last_name}}</td>
                                            <td>{{$return->business->name}}</td>
                                            <td>{{$return->businessLocation->name}}</td>
                                            <td>{{$return->total_sales}}</td>
                                            <td>
                                                <a href="" class="btn btn-info btn-sm" data-toggle="tooltip"
                                                   data-placement="right" title="View">
                                                    <i class="bi bi-eye-fill"></i>
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
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
    <script>
        $(document).ready(function() {
            $(".nav-tabs a").click(function() {
                $(this).tab('show');
            });
        });
    </script>
@endsection