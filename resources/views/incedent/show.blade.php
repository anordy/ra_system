@extends('layouts.master')

@section('title', 'Show Incedent Details')

@section('content')
   
<div class="card mt-3">
    <div class="card-header font-weight-bold bg-white d-flex justify-content-between align-items-center">
        <span>   Incedent Information  #{{ $incedent->reference ?? 'N/A'  }}</span>
    </div>

    <div class="card-body">
        <div class="row my-2">
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Status</span>
                <p class="my-1">
                    @if($incedent->status === \App\Enum\RaStatus::PENDING)
                        <span class="badge badge-info py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ __('Pending') }}
            </span>
                    @elseif($incedent->status === \App\Enum\RaStatus::APPROVED)
                        <span class="badge badge-success py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ __('Registered') }}
            </span>
                    @elseif($incedent->status === \App\Enum\RaStatus::REJECTED)
                        <span class="badge badge-warning py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ __('Rejected') }}
            </span>
                    @else
                        <span class="badge badge-primary py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ $incedent->status }}
            </span>
                    @endif
                </p>
            </div>
 
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Bank Channel</span>
                <p class="my-1">{{ $incedent->channel->name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Name Of Incedent</span>
                <p class="my-1">{{ $incedent->name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Symptom Of The Incedent</span>
                <p class="my-1">{{ $incedent->symptom_of_incident ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Real Issue</span>
                <p class="my-1">{{ $incedent->real_issue ? 'Yes': 'No' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Impact Potential Revenue</span>
                <p class="my-1">{{ $incedent->impact_revenue ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Impact Potential Customer</span>
                <p class="my-1">{{ $incedent->impact_customers ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Impact Potential System</span>
                <p class="my-1">{{ $incedent->impact_system ?? 'N/A' }}</p>
            </div>
         
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Incedent Reported Date</span>
                <p class="my-1">{{ \Carbon\Carbon::parse($incedent->incident_reported_date)->format('YMD') ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Open By</span>
                <p class="my-1">{{ $incedent->reportedBy->fullname ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Problem Owner</span>
                <p class="my-1">{{ $incedent->reportedBy->fullname ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Affected Revenue Stream</span>
                <p class="my-1">{{ $incedent->affected_rev_stream ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Affected System</span>
                <p class="my-1">{{ $incedent->system->name ?? 'N/A' }}</p>
            </div>

            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Action (Option/Resolution)</span>
                <p class="my-1">{{ $incedent->action_taken ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Additional Value (Revenue Assurance)</span>
                <p class="my-1">{{ $incedent->additional_ra ?? 'N/A' }}</p>
            </div>
            
        </div>
    </div>

</div>

<div class="card">
    <div class="card-header">
        Revenue Leakage
        <div class="card-tools">
            {{-- @if(approvalLevel(Auth::user()->level_id, 'Maker')) --}}
            <button class="btn btn-primary"
                                onclick="Livewire.emit('showModal','incedent.update-revenue-modal','{{ encrypt($incedent->id) }}')">
                            <i class="bi bi-arrow-left-right mr-1"></i>
                            Update
                        </button>
            {{-- @endif --}}
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>
                        <strong>Revenue Type</strong>
                    </th>
                   
                    <th >
                        <strong>Detected</strong>
                    </th>
                    <th >
                        <strong>Prevented</strong>
                    </th>
                    <th >
                        <strong>Recovered</strong>
                    </th>
                    <th>
                        <strong>Currency</strong>
                    </th>
                   
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($leakages as $index => $row)
                    <tr>
                        <td>
                            {{ $row->type }}
                        </td>
                       
                        <td>
                            {{ number_format($row->detected) }}
                        </td>
                        <td>
                            {{ number_format($row->prevented) }}
                        </td>
                        <td>
                            {{ number_format($row->recovered) }}
                        </td>
                        <td>
                            {{ $row->currency }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection