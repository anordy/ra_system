@extends('layouts.master')

@section('title', 'Home')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-c-blue order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Staff</h6>
                    <h2 class="text-right"><i
                            class="bi bi-person-badge f-left"></i><span>{{ $counts['users'] }}</span></h2>
                    <p class="mb-0"><a href="{{ route('settings.users.index') }}">click here to view more</a></p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-c-green order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Taxpayers</h6>
                    <h2 class="text-right"><i
                            class="bi bi-person-check-fill f-left"></i><span>{{ $counts['taxpayers'] }}</span></h2>
                    <p class="mb-0"><a href="{{ route('taxpayers.taxpayer.index') }}">click here to view more</a></p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-c-yellow order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Businesses</h6>
                    <h2 class="text-right"><i
                            class="bi bi-building f-left"></i><span>{{ $counts['businesses'] }}</span>
                    </h2>
                    <p class="mb-0"><a href="{{ route('business.registrations.index') }}">click here to view more</a></p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-c-pink order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Tax Agents</h6>
                    <h2 class="text-right"><i
                            class="bi bi-credit-card f-left"></i><span>{{ $counts['taxAgents'] }}</span>
                    </h2>
                    <p class="mb-0"><a href="{{ route('taxagents.active') }}">click here to view more</a></p>
                </div>
            </div>
        </div>
    </div>

    @if (count($issues) > 0)
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <h6 class="m-2">System Issues</h6>

                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th># Issue</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($issues as $issue)
                                <tr>
                                    <th>{{ $issue['description'] }}</th>
                                    <td><a class="btn btn-info rounded btn-sm" href="{{ route($issue['route']) }}"><i
                                                class="bi bi-gear-wide-connected"></i> Configure</a></td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

@endsection
