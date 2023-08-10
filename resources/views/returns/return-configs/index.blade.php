@extends('layouts.master')

@section('css')
    <link href="{{ asset('plugins/datatables/datatables.min.css') }}" rel="stylesheet" />
@endsection

@section('title', 'Returns Configurations')

@section('content')
    <div class="d-flex justify-content-end pb-2">
        <a class="btn btn-info" href="{{ route('settings.return-config.index') }}">
            <i class="bi bi-arrow-return-left mr-2"></i>
            Back
        </a>
    </div>
    <div class="card">
        <div class="card-header bg-white text-uppercase font-weight-bold">
            {{ $code }} return configurations
            {{--            <div class="card-tools"> --}}
            {{--                <a href="{{route('settings.return-config.create', [encrypt($id), encrypt($code)])}}" class="btn btn-info btn-sm"><i class="fa fa-plus-circle"></i> --}}
            {{--                    New Configuration</a> --}}
            {{--            </div> --}}
        </div>
        <div class="card-body">
            
            @if ($code == \App\Models\TaxType::LUMPSUM_PAYMENT || $code == 'lumpsum payment')
                <table class="table table-bordered myTable1">
                    <thead>
                        <tr>
                            <th>No:</th>
                            <th>Mauzo kwa Mwaka</th>
                            <th>Malipo kwa Mwaka</th>
                            <th>Malipo kwa Miezi Mitatu</th>
                            <th>Currency</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($configs as $index => $config)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ number_format($config->min_sales_per_year,2)}} - {{ number_format($config->max_sales_per_year,2) }}</td>
                                <td>{{ number_format($config->payments_per_year,2) }}</td>
                                <td> {{ number_format($config->payments_per_installment,2) }}</td>
                                <td>{{ $config->currency }}</td>
                                <td>
                                    @can('setting-return-configuration-edit')
                                        <a href="{{ route('settings.return-config.edit', [encrypt($id), encrypt($code), encrypt($config->id)]) }}"
                                            class="btn btn-outline-success">
                                            <i class="bi bi-pencil pr-1"></i>Edit
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <table class="table table-bordered myTable">
                    <thead>
                        <tr>
                            <th>No:</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Rate Applicable</th>
                            <th>Rate</th>
                            <th>Currency</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($configs as $index => $config)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $config->name }}</td>
                                <td>{{ $config->code }}</td>
                                <td>
                                    @if ($config->rate_applicable == 0)
                                        <span class="badge badge-danger py-1 px-2"
                                            style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%"><i
                                                class="bi bi-x-circle-fill mr-1"></i>No
                                        </span>
                                    @elseif($config->rate_applicable == 1)
                                        <span class="badge badge-success py-1 px-2"
                                            style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%"><i
                                                class="bi bi-check-circle-fill mr-1"></i>Yes
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if ($config->currency == 'TZS')
                                        {{ number_format($config->rate, 2) }}
                                    @elseif ($config->currency == 'USD')
                                        {{ number_format($config->rate_usd, 2) }}
                                    @endif
                                    <strong>
                                        @if ($config->rate_type == 'percentage')
                                            %
                                        @endif
                                    </strong>
                                </td>
                                <td>{{ $config->currency }}</td>
                                <td>
                                    @can('setting-return-configuration-edit')
                                        <a href="{{ route('settings.return-config.edit', [encrypt($id), encrypt($code), encrypt($config->id)]) }}"
                                            class="btn btn-outline-success">
                                            <i class="bi bi-pencil pr-1"></i>Edit
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('plugins/datatables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.myTable1').DataTable();
            $('.myTable').DataTable();
        });
    </script>

@endsection
