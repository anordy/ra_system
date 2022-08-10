@extends('layouts.master')

@section('title','Tax Claims')

@section('content')
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Claim Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Name</span>
                    <p class="my-1">{{ $claim->business->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Financial Month</span>
                    <p class="my-1">{{ $claim->financialMonth->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Financial Year</span>
                    <p class="my-1">{{ $claim->financialMonth->year->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Claim Status</span>
                    <p class="my-1">{{ $claim->status }}</p>
                </div>

                <div class="col-md-12 mt-3">
                    <ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-bottom: 0;">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="new-return-tab" data-toggle="tab" href="#new-return" role="tab" aria-controls="new-return" aria-selected="true">New Return</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="old-return-tab" data-toggle="tab" href="#old-return" role="tab" aria-controls="old-return" aria-selected="false">Old Return</a>
                        </li>
                    </ul>
                    <div class="tab-content bg-white border shadow-sm" id="myTabContent" style="padding: 1rem !important;">
                        <div class="tab-pane fade show active" id="new-return" role="tabpanel" aria-labelledby="new-return-tab">
                            <table class="table table-bordered normal-text">
                                <thead>
                                <th style="width: 30%">Item Name</th>
                                <th style="width: 20%">Value (TZS)</th>
                                <th style="width: 10%">Rate</th>
                                <th style="width: 20%">Tax (TZS)</th>
                                </thead>
                                <tbody>
                                @foreach ($newReturn->items as $item)
                                    @php
                                        $oldItem = $oldReturn->items()->where('config_id', $item->config->id)->firstOrFail();
                                    @endphp
                                    <tr>
                                        <td>{{ $item->config->name }}</td>
                                        <td class="{{ $item->value != $oldItem->value ? 'bg-alt' : '' }}">{{ number_format($item->value) }}</td>
                                        <td>{{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate_usd }}</td>
                                        <td class="{{ $item->value != $oldItem->value ? 'bg-alt' : '' }}">{{ number_format($item->tax) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th style="width: 20%"></th>
                                    <th style="width: 30%"></th>
                                    <th style="width: 25%"></th>
                                    <th style="width: 25%" class="{{ $oldReturn->total_amount_due != $newReturn->total_amount_due ? 'bg-alt' : '' }}">{{ number_format($newReturn->total_amount_due) }}</th>
                                </tr>

                                </tfoot>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="old-return" role="tabpanel" aria-labelledby="old-return-tab">
                            <table class="table table-bordered normal-text">
                                <thead>
                                    <th style="width: 30%">Item Name</th>
                                    <th style="width: 20%">Value (TZS)</th>
                                    <th style="width: 10%">Rate</th>
                                    <th style="width: 20%">Tax (TZS)</th>
                                </thead>
                                <tbody>
                                @foreach ($oldReturn->items as $item)
                                    @php
                                        $newItem = $newReturn->items()->where('config_id', $item->config->id)->firstOrFail();
                                    @endphp
                                    <tr>
                                        <td>{{ $item->config->name }}</td>
                                        <td class="{{ $item->value != $newItem->value ? 'bg-alt' : '' }}">{{ number_format($item->value) }}</td>
                                        <td>{{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate_usd }}</td>
                                        <td class="{{ $item->value != $newItem->value ? 'bg-alt' : '' }}">{{ number_format($item->tax) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th style="width: 20%"></th>
                                    <th style="width: 30%"></th>
                                    <th style="width: 25%"></th>
                                    <th style="width: 25%" class="{{ $oldReturn->total_amount_due != $newReturn->total_amount_due ? 'bg-alt' : '' }}">{{ number_format($oldReturn->total_amount_due) }}</th>
                                </tr>

                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (count($claim->officers) > 0)
        <div class="card rounded-0">
            <div class="card-header text-uppercase font-weight-bold bg-white">
                Compliance Officers
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($claim->officers as $officer)
                        <div class="col-md-6 mb-3">
                                    <span class="font-weight-bold text-uppercase">Team
                                        {{ $officer->team_leader ? 'Leader' : 'Member' }}</span>
                            <p class="my-1">{{ $officer->user->full_name ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if ($claim->assessment)
        <div class="card">
            <div class="card-header text-uppercase font-weight-bold bg-white">
                Assessment Details
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div style="background: #faf5f5; color: #863d3c; border: .5px solid #863d3c24;"
                             class="p-2 mb-3 d-flex rounded-sm align-items-center">
                            <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                            <a target="_blank"
                               href="{{ route('claims.files.show', encrypt($claim->assessment->report_path)) }}"
                               style="font-weight: 500;" class="ml-1">
                                Assessment Report
                                <i class="bi bi-arrow-up-right-square ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <livewire:approval.tax-claim-approval-processing modelName="{{ get_class($claim) }}" modelId="{{ $claim->id }}" />

@endsection