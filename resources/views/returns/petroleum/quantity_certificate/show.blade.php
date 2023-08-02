@extends('layouts.master')

@section('title', 'Certificate of Quantity')

@section('content')
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="certificate-tab" data-toggle="tab" href="#certificate" role="tab"
                aria-controls="certificate" aria-selected="true">Certificate of Quantity Information</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="approval-tab" data-toggle="tab" href="#approval" role="tab" aria-controls="approval"
                aria-selected="false">Approval History</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active card p-2" id="certificate" role="tabpanel" aria-labelledby="certificate-tab">
            @livewire('returns.petroleum.quantity-certificate-show', ['id' => $id])
        </div>
        <div class="tab-pane fade card p-2" id="approval" role="tabpanel" aria-labelledby="approval-tab">
            <livewire:approval.approval-history-table modelName='App\Models\Returns\Petroleum\QuantityCertificate'
                modelId="{{ $id }}" />
        </div>
    </div>
@endsection
