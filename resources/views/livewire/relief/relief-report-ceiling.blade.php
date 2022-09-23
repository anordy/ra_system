@extends('layouts.master')

@section('title', 'Reliefs Report')

@section('content')
    <div class="d-flex justify-content-start mb-3">
        <a href="{{ url()->previous() }}" class="btn btn-info">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
    <div class="row px-5">
        <div class="col-sm-9">
            <div class="card">
                <div class="shadow rounded">
                    <div class="card-header text-uppercase font-weight-bold bg-grey ">
                        CEILING REPORT
                    </div>
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr class="text-center">
                                <th>
                                    <strong>S/N</strong>
                                </th>
                                <th>
                                    <strong>BENEFICIARIES INSTITUTIONS</strong>
                                </th>
                                <th>
                                    <strong>DONORS</strong>
                                </th>
                                <th>
                                    <strong>VAT SPECIAL RELIEF (Tsh)</strong>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $mainIndex = 0;
                                $total = 0;
                            @endphp
                            @foreach ($projectSections as $projectSection)
                                <tr class="text-center">
                                    <td></td>
                                    <td><strong> {{ $projectSection['name'] }}</strong></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @foreach ($projectSection['projects'] as $index => $project)
                                    @php
                                        $mainIndex++;
                                    @endphp
                                    <tr>
                                        <td>{{ $mainIndex }}</td>
                                        <td>{{ $project['name'] }}</td>
                                        <td>{{ $project['sponsor'] }}</td>
                                        <td class="bg-secondary text-right">{{ number_format($project['relievedAmount'], 1) }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td class="bg-secondary font-weight-bold"> <strong>SUB TOTAL</strong></td>
                                    <td class="bg-secondary font-weight-bold"></td>
                                    <td class="bg-secondary font-weight-bold text-right">
                                        <strong>{{ number_format($projectSection['subTotal'], 1) }}</strong></td>
                                </tr>
                                @php
                                    $total += $projectSection['subTotal'];
                                @endphp
                            @endforeach
                            <tr>
                                <td class="bg-secondary font-weight-bold"></td>
                                <td class="bg-secondary font-weight-bold"> <strong>GRAND TOTAL</strong></td>
                                <td class="bg-secondary font-weight-bold"></td>
                                <td class="bg-secondary font-weight-bold text-right"> <strong> {{ number_format($total, 1) }}
                                    </strong></td>
                            </tr>
                        </tbody>
                    </table>
        
                </div>
            </div>
        </div>
        <div class="col-sm-3"></div>
    </div>
    
@endsection
