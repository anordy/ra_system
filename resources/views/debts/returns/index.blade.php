@extends('layouts.master')

@section('title', 'Return Debts')

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            Return Debts
        </div>
        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab1" class="nav-item nav-link font-weight-bold active">Large Taxpayer Department</a>
                <a href="#tab2" class="nav-item nav-link font-weight-bold">Domestic Tax Department</a>
                <a href="#tab3" class="nav-item nav-link font-weight-bold">Non Tax Revenue Department</a>
                <a href="#tab4" class="nav-item nav-link font-weight-bold">Pemba</a>
            </nav>
            <div class="tab-content px-2 card pt-3 pb-2">
                <div id="tab1" class="tab-pane fade m-2 show active">
                    <livewire:debt.return-overdue-debts-table department="{{ \App\Models\Region::LTD  }}" />
                </div>
                <div id="tab2" class="tab-pane fade m-2 show">
                    <livewire:debt.return-overdue-debts-table department="{{ \App\Models\Region::DTD  }}" />
                </div>
                <div id="tab3" class="tab-pane fade m-2 show">
                    <livewire:debt.return-overdue-debts-table department="{{ \App\Models\Region::NTRD  }}" />
                </div>
                <div id="tab4" class="tab-pane fade m-2 show">
                    <livewire:debt.return-overdue-debts-table department="{{ \App\Models\Region::PEMBA  }}" />
                </div>
            </div>
        </div>
    </div>
@endsection