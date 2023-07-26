<div class="card shadow-none">
    @include('layouts.component.messages')

    @if ($certificate->status == 'correction')
        @livewire('returns.petroleum.quantity-certificate-edit', ['id' => encrypt($certificate->id)])
    @else
    <div class="card-body">
        <h6>Taxpayer & Vessel Information</h6>
        <div class="row">
            <div class="form-group col-lg-6">
                <label class="control-label">Name of Importer/Market (ZRA No.)</label>
                <div type="text" class="form-control disabled">{{ $business ?? '' }}</div>
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Ascertained Date</label>
                <div type="text" class="form-control disabled">{{ $ascertained }}</div>
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Name of Ship</label>
                <div type="text" class="form-control disabled">{{ $ship ?? '' }}</div>
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Port of Disembarkation</label>
                <div type="text" class="form-control disabled">{{ $port ?? '' }}</div>

            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Voyage No:</label>
                <div type="text" class="form-control disabled">{{ $voyage_no ?? '' }}</div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <h6>Product Information</h6>

        @foreach ($products as $key => $product)
            <div class="mt-2 border p-2">
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label class="control-label">Intended Cargo Discharge</label>
                        <div class="form-control">{{ $product['cargo_name'] ?? '' }}</div>
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="control-label">Liters Observed</label>
                        <div class="form-control">{{ $product['liters_observed'] ?? '' }}</div>
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="control-label">Liters At 20 <sup>o</sup> C</label>
                        <div class="form-control">{{ $product['liters_at_20'] ?? '' }}</div>

                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Metric Tons in Air</label>
                        <div class="form-control">{{ $product['metric_tons'] ?? '' }}</div>

                    </div>

                </div>
            </div>
        @endforeach

        <livewire:approval.quantity-certificate-approval-processing modelName="{{ get_class($certificate) }}" modelId="{{ encrypt($certificate->id) }}"></livewire:approval.quantity-certificate-approval-processing>

    </div>
    @endif


</div>
