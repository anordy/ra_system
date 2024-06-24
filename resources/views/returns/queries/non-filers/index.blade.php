@extends('layouts.master')

@section('css')
    <link href="{{ asset('plugins/datatables/datatables.min.css') }}" rel="stylesheet"/>
@endsection

@section('title','Return Queries')

@section('content')
    <div class="card">
        <div class="card-header">All Non Filers for three consecutive months</div>
        <div class="card-body">
            <table class="table table-bordered myTable">
                <thead>
                <tr>
                    <th>No:</th>
                    <th>Tax Payer</th>
                    <th>Business Name</th>
                    <th>Business Location</th>
                    <th>Tax Type</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                @if(!empty($non_filers))
                    @foreach($non_filers as $index=>$non_filer)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$non_filer->business->taxpayer->first_name ?? 'N/A'}} {{$non_filer->business->taxpayer->last_name ?? 'N/A'}}</td>
                            <td>{{$non_filer->business->name ?? 'N/A'}}</td>
                            <td>{{$non_filer->businessLocation->name ?? 'N/A'}}</td>
                            <td>{{$non_filer->taxType->name ?? 'N/A'}}</td>
                            <td>
                                <a href="{{route('queries.non-filers.show',[encrypt($non_filer->id)])}}"
                                   class="btn btn-info btn-sm" data-toggle="tooltip"
                                   data-placement="right" title="View">
                                    <i class="bi bi-eye-fill"></i>
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
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