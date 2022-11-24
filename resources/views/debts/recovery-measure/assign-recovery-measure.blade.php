@extends('layouts.master')

@section('title')
    Assign Recovery Measure
@endsection

@section('content')
    <nav class="nav nav-tabs mt-0 border-top-0">
        <a href="#tab1" class="nav-item nav-link font-weight-bold active">Debt Details</a>
        <a href="#tab2" class="nav-item nav-link font-weight-bold">Approval History</a>
    </nav>
    <div class="tab-content px-2 card pt-3 pb-2">
        <div id="tab1" class="tab-pane fade active show m-4">
            @livewire('debt.recovery-measure.assign-recovery-measure', ['debtId' => $debtId])
        </div>
        <div id="tab2" class="tab-pane fade m-4">
            <livewire:approval.approval-history-table modelName='App\Models\Debts\RecoveryMeasure'
                modelId="{{ $debtId }}" />
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".nav-tabs a").click(function() {
                $(this).tab('show');
            });
        });
    </script>
@endsection
