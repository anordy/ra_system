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
        </div>
        <div class="card-body">
            
            @if ($code == \App\Models\TaxType::LUMPSUM_PAYMENT)
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
                    @if($configs)
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
                    @endif
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
                    @if($configs)
                        @foreach ($configs as $i => $conf)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $conf->name }}</td>
                                <td>{{ $conf->code }}</td>
                                <td>
                                    @if ($conf->rate_applicable == 0)
                                        <span class="badge badge-danger py-1 px-2"><i
                                                class="bi bi-x-circle-fill mr-1"></i>No
                                        </span>
                                    @elseif($conf->rate_applicable == 1)
                                        <span class="badge badge-success py-1 px-2"><i
                                                class="bi bi-check-circle-fill mr-1"></i>Yes
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if ($conf->currency == 'TZS')
                                        {{ number_format($conf->rate, 2) }}
                                    @elseif ($conf->currency == 'USD')
                                        {{ number_format($conf->rate_usd, 2) }}
                                    @endif
                                    <strong>
                                        @if ($conf->rate_type == 'percentage')
                                            %
                                        @endif
                                    </strong>
                                </td>
                                <td>{{ $conf->currency }}</td>
                                <td>
                                    @can('setting-return-configuration-edit')
                                        <a href="{{ route('settings.return-config.edit', [encrypt($id), encrypt($code), encrypt($conf->id)]) }}"
                                            class="btn btn-outline-success">
                                            <i class="bi bi-pencil pr-1"></i>Edit
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    @endif
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
