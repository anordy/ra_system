<div class="card">

    <div class="card-header text-uppercase font-weight-bold bg-white">
        {{ __('Filing') }} {{ $taxType->name ?? 'N/A' }} {{__('Tax Return for')}} {{ $fillingMonth->name ?? 'N/A' }}
        , {{ $fillingMonth->year->code ?? 'N/A' }}
    </div>

    <div class="card-body">
        <div class="row border rounded mx-1 p-2 mb-3">
            <div class="col-md-3">
                <span class="font-weight-bold text-uppercase">{{ __('Return Currency') }}</span>
                <p class="my-1">{{ $taxTypeCurrency ?? '' }}</p>
            </div>
            <div class="col-md-3">
                <span class="font-weight-bold text-uppercase">{{ __('Today Exchange Rate') }}</span>
                <p class="my-1"> 1 {{ exchangeRate()->currency ?? '' }} = {{ exchangeRate()->mean ?? '' }} TZS</p>
            </div>
            <div class="col-md-3">
                <span class="font-weight-bold text-uppercase">{{ __('Interest Rate') }}</span>
                <p class="my-1">{{ interestRate() ?? '' }}</p>
            </div>
        </div>
        <div class="row border rounded mx-1 p-2 mb-3">
            @if($taxType->code === \App\Models\TaxType::CHARTERED_FLIGHT)
                    <div class="col-md-3 form-group">
                        <label class="font-weight-bold text-uppercase">Private Name</label>
                        <input type="text" maxlength="40" wire:model.defer="companyName"
                               class="form-control @error('companyName') is-invalid @enderror">
                        @error('companyName')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                <div class="col-md-3 form-group">
                    <label for="zin">{{ __('Select Passengers Type') }}:</label>
                    <select wire:model="passengersType"
                            class="form-control {{ $errors->has('passengersType') ? 'is-invalid' : '' }}">
                        <option value="{{ \App\Models\Returns\Chartered\CharteredReturn::LOCAL }}">{{ __('Local') }}</option>
                        <option value="{{ \App\Models\Returns\Chartered\CharteredReturn::FOREIGN  }}">{{ __('Foreign') }}</option>
                    </select>
                </div>
            @endif

        </div>

        <table class="table table-bordered">
            <thead>
            <tr class="table-active">
                <th class="text-center">Service Rendered <br> (Huduma Iliyotolewa)</th>
                <th class="text-center">Number of Pax <br> (Idadi ya Abiria)</th>
                <th class="text-center">Rate <br> (Kiwango)</th>
                <th class="text-center">Levy Amount <br> (Kiasi cha Kodi)</th>
            </tr>
            </thead>
            <tbody>
            @if(!empty($configs))
                @foreach ($configs as $key => $config)
                    @if (in_array($config['col_type'], ['total', 'subtotal']))
                        <tr>
                            <td class="return-label">{{ $config['name'] }}</td>
                            <td class="bg-secondary" colspan="1"></td>
                            <td class="bg-secondary" colspan="1"></td>
                            <td class="return-label">
                                @php
                                    echo number_format($this->totalVatCalculate($key, $config['formular'], 2));
                                @endphp
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="return-label">{{ $config['name'] }}</td>
                            <td class="return-label">
                                @if ($config['value_calculated'])
                                    @php
                                        echo number_format($this->valueCalculated($key, $config['value_formular']));
                                    @endphp
                                @else
                                    <div class="input-group">
                                        <input type="text" x-data x-mask:dynamic="$money($input)"
                                               @if($passengersType === \App\Models\Returns\Chartered\CharteredReturn::LOCAL && in_array($config['code'], ['NFAT', 'NFSF'])) disabled
                                                @elseif($passengersType === \App\Models\Returns\Chartered\CharteredReturn::FOREIGN && in_array($config['code'], ['NLAT', 'NLSF'])) disabled @endif
                                               value="{{$this->manualValidation($key)}}"
                                               class="form-control rounded @error("configs.".$key.".value")  is-invalid @enderror"
                                               wire:model="configs.{{ $key }}.value">
                                        @error('configs.' . $key . '.value')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                @endif
                            </td>
                            <td class="return-label">
                                @if ($config['rate_type'] == 'fixed')
                                    @if ($config['currency'] == 'both')
                                        {{ $config['rate'] }} TZS <br>
                                        {{ $config['rate_usd'] }} USD
                                    @elseif ($config['currency'] == 'TZS')
                                        {{ $config['rate'] }} TZS
                                    @elseif ($config['currency'] == 'USD')
                                        {{ $config['rate_usd'] }} USD
                                    @endif
                                @elseif ($config['rate_type'] == 'percentage')
                                    {{ $config['rate'] }} %
                                @endif
                            </td>
                            <td class="return-label">
                                @php
                                    echo number_format($this->singleVatCalculate($key), 2);
                                @endphp
                            </td>
                        </tr>
                    @endif

                @endforeach
            @endif

            </tbody>
        </table>

        @if($taxType->code === \App\Models\TaxType::CHARTERED_SEA)
            <div class="d-flex mt-4 mb-4 my-3">
                <span class="font-weight-bold mr-5 mt-1">
                    Summary of Manifest Attachment
                </span>
                <div class="mr-2 flex-1" x-init="isUploading = false" x-data="{ isUploading: false, progress: 0 }"
                     x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false"
                     x-on:livewire-upload-error="isUploading = false"
                     x-on:livewire-upload-progress="progress = $event.detail.progress">
                    <input type="file" required
                           accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, .csv"
                           class="form-control @error('manifestAttachment') is-invalid @enderror"
                           wire:model="manifestAttachment">
                    @error('manifestAttachment')
                    <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                    <div x-show="isUploading">
                        <progress max="100" x-bind:value="progress"></progress>
                    </div>
                </div>

            </div>
            <div class="text-secondary small">
                    <span class="font-weight-bold">
                        {{ __('Note') }}:
                    </span>
                <span class="">
                        {{ __('All documents must be in less than 3 MB in size') }}
                    </span>
            </div>
        @endif

        <div class="d-flex justify-content-end">
            <button wire:click="toggleSummary(true)" class="btn btn-primary px-3 ml-2 mt-3" type="button"
                    wire:loading.attr="disabled">
                <i class="bi bi-arrow-return-right mr-2" wire:loading.remove wire:target="toggleSummary(true)"></i>
                <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                   wire:target="toggleSummary(true)"></i>
                {{ __('Next') }}
            </button>

        </div>
    </div>

</div>
