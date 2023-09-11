<div>
    @foreach ($locations as $location)
        <div id="accordion">
            <div class="card">
                <button class="btn collapsed" data-toggle="collapse" data-target="#collapseLocation-{{ $location->id }}"
                    aria-expanded="false" aria-controls="collapseLocation-{{ $location->id }}">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            {{ $location->name }}
                            <span class="ml-2">
                                <i class="bi bi-chevron-double-down"></i>
                            </span>
                        </h5>
                    </div>
                </button>

                <div id="collapseLocation-{{ $location->id }}" class="collapse" aria-labelledby="headingTwo"
                    data-parent="#accordion">
                    <div class="card-body">
                        @foreach ($taxTypes as $taxType)
                            <div id="accordionInstance">
                                <div class="card">
                                    <button class="btn collapsed" data-toggle="collapse" data-target="#collapseTaxType-{{ $location->id }}-{{ $taxType->id }}"
                                        aria-expanded="false" aria-controls="collapseTaxType-{{ $location->id }}-{{ $taxType->id }}">
                                        <div class="card-header h6" style="color: #0080c1" id="headingTwo">
                                            <div class="d-flex flex-column">
                                                <div>
                                                    {{ $taxType->name }}
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column-reverse">
                                                <div>
                                                    <i class="bi bi-chevron-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                    
                                    <div id="collapseTaxType-{{ $location->id }}-{{ $taxType->id }}" class="collapse" aria-labelledby="headingTwo"
                                        data-parent="#accordionInstance">
                                        <div class="card-body">
                                            @livewire('investigation.declared-sales-analysis', ['investigationId' => encrypt($investigation->id), 'tax_type_id' => encrypt($taxType->id), 'location_id' => encrypt($location->id)])
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
