@extends('layouts.master')

@section('title', 'Taxpayers')

@section('content')
    @livewire('returns.return-summary', ['vars' => $vars])
    @livewire('returns.return-card-report', ['paidData' => $paidData, 'unpaidData' => $unpaidData])
    
    <div class="card mt-3">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Mobile Money Transfer Return
        </div>
        <div class="card-body">
            
            <div>
                <ul style="border-bottom: unset !important;" class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#normal-return" role="tab"
                            aria-controls="home" aria-selected="true">Normal Returns</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="debt-returns-tab" data-toggle="tab" href="#debt-returns" role="tab"
                            aria-controls="profile" aria-selected="false">Debt Returns</a>
                    </li>
                </ul>
                <div style="border: 1px solid #eaeaea;" class="tab-content" id="myTabContent">
    
                    <div class="tab-pane p-2 show active" id="normal-return" role="tabpanel" aria-labelledby="normal-return-tab">
                        <livewire:returns.excise-duty.mobile-money-transfer-table />
                        
                    </div>
    
                    <div class="tab-pane p-2" id="debt-returns" role="tabpanel" aria-labelledby="debt-returns-tab">
                        <livewire:returns.excise-duty.mobile-money-transfer-debt-table />
    
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
