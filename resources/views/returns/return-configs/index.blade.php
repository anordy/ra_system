@extends('layouts.master')

@section('css')
    <link href="{{ asset('plugins/datatables/datatables.min.css') }}" rel="stylesheet"/>
@endsection

@section('title','Returns Configurations')

@section('content')
    <div class="d-flex justify-content-end pb-2">
        <a class="btn btn-info" href="{{ route('settings.return-config.index') }}">
            <i class="bi bi-arrow-return-left mr-2"></i>
            Back
        </a>
    </div>
    <div class="card">
        <div class="card-header ">
            <h6 class="text-capitalize">{{$code}} return configurations</h6>
            <div class="card-tools">
                <a href="{{route('settings.return-config.create', [encrypt($id), encrypt($code)])}}" class="btn btn-info btn-sm"><i class="fa fa-plus-circle"></i>
                    New Configuration</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered myTable">
                <thead>
                <tr>
                    <th>No:</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Rate Applicable</th>
                    <th>Rate</th>
                    <th>Currency</th>
                    <th>Financial year</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($configs as $index=>$config)
                    <tr>
                        <td>{{$index + 1}}</td>
                        <td>{{$config->name}}</td>
                        <td>{{$config->code}}</td>
                        <td>
                            @if($config->rate_applicable == 0)
                                <span class="badge badge-danger py-1 px-2"
                                      style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%"><i class="bi bi-x-circle-fill mr-1"></i>No
                                </span>

                            @elseif($config->rate_applicable == 1)
                                <span class="badge badge-success py-1 px-2"
                                      style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%"><i class="bi bi-check-circle-fill mr-1"></i>Yes
                                </span>
                            @endif
                        </td>

                        <td>{{$config->rate}}
                            <strong>
                                @if($config->rate_type == 'percentage')
                                    %
                                @endif
                            </strong></td>
                        <td>{{$config->currency}}</td>
                        <td>
                        {{\App\Http\Controllers\Returns\ReturnController::getFinancialYear($config->financial_year_id)}}
                        </td>
                        <td>
                            <a href="{{route('settings.return-config.edit', [encrypt($id), encrypt($code),encrypt($config->id)])}}" class="btn btn-outline-success"><i class="bi bi-pencil "></i>Edit</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('plugins/datatables/datatables.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.myTable').DataTable();
        });
    </script>

@endsection