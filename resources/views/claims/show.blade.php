@extends('layouts.master')

@section('title','Tax Claims')

@section('content')
    @if($claim->status == \App\Enum\TaxClaimStatus::PENDING)
        <div class="card rounded-0">
            <div class="card-header bg-white font-weight-bold">
                Payment Assignments
            </div>
            <div class="card-body">
                    <livewire:claims.claims-approval :claim="$claim" />
            </div>
        </div>
    @endif
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
                                <th style="width: 20%">Value</th>
                                <th style="width: 10%">Rate</th>
                                <th style="width: 20%">Tax</th>
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
                                    <th style="width: 20%">Value</th>
                                    <th style="width: 10%">Rate</th>
                                    <th style="width: 20%">Tax</th>
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

@endsection