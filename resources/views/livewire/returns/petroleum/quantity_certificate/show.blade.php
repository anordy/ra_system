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
            @if($certificate->quantity_certificate_attachment)
                <div class="form-group col-lg-6">
                    <div style="background: #faf5f5; color: #036a9e; border: .5px solid #036a9e24;"
                         class="p-2 mb-3 d-flex rounded-sm align-items-center">
                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                        <a target="_blank"
                           href="{{ route('petroleum.certificateOfQuantity.attachment', encrypt($certificate->id)) }}"
                           style="font-weight: 500;" class="ml-1">
                            Certificate of Quantity -  Attachment
                            <i class="bi bi-arrow-up-right-square ml-1"></i>
                        </a>
                    </div>
                </div>
            @endif
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
                        <div class="form-control">{{ number_format($product['liters_observed'], 2) ?? '' }}</div>
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="control-label">Liters At 20 <sup>o</sup> C</label>
                        <div class="form-control">{{ number_format($product['liters_at_20'], 2) ?? '' }}</div>

                    </div>
                    <div class="form-group col-lg-6">
                        <label class="control-label">Metric Tons in Air</label>
                        <div class="form-control">{{ number_format($product['metric_tons'], 2) ?? '' }}</div>

                    </div>

                </div>
            </div>
        @endforeach

        <livewire:approval.quantity-certificate-approval-processing modelName="{{ get_class($certificate) }}" modelId="{{ encrypt($certificate->id) }}"></livewire:approval.quantity-certificate-approval-processing>

    </div>
    @endif


</div>
