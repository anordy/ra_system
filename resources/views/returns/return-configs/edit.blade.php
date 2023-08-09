@extends('layouts.master')

@section('title','Returns Configurations Edit')

@section('content')
    <div class="d-flex justify-content-end pb-2">
        <a class="btn btn-info" href="{{ route('settings.return-config.show', encrypt($taxtype_id)) }}">
            <i class="bi bi-arrow-return-left mr-2"></i>
            Back
        </a>
    </div>
    <div class="card">
        <div class="card-header bg-white text-uppercase font-weight-bold">
            editing {{$code}} return configurations
        </div>
        <div class="card-body">
            @if ($code == \App\Models\TaxType::LUMPSUM_PAYMENT || $code == 'lumpsum payment')
                <div>
                    <form action="{{ route('settings.return-config.edit.lumpSum', ['config_id' => encrypt($configs->id)]) }}" method="post">
                        @csrf
                        <div>
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <label>Mauzo Kuanzia</label>
                                    <input type="text" name="min_sales_per_year" value="{{ $configs->min_sales_per_year }}" class="form-control form-control-lg">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label>Hadi</label>
                                    <input type="text" name="max_sales_per_year" value="{{ $configs->max_sales_per_year }}" class="form-control form-control-lg">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label>Malipo kwa Mwaka</label>
                                    <input type="text" name="payments_per_year" value="{{ $configs->payments_per_year }}" class="form-control form-control-lg">
                                </div>
                            
                                <div class="col-md-3 mb-2">
                                    <label>Malipo Kwa Miezi Mitatu</label>
                                    <input type="text" name="payments_per_installment" value="{{ $configs->payments_per_installment }}" class="form-control form-control-lg">
                                </div>
                    
                                <div class="col-md-12 d-flex justify-content-end">
                                    @can('setting-return-configuration-edit')
                                    <button type="submit" class="btn btn-success px-5">
                                        Update
                                    </button>
                                    @endcan
                                </div>
                            
                            </div>
                        </div>
                    </form>
                    
                </div>
            @else
            <livewire:returns.edit-return-config config_id="{{$config_id}}" taxtype_id="{{$taxtype_id}}"/>
            @endif
        </div>
    </div>
@endsection

@section('scripts')

@endsection