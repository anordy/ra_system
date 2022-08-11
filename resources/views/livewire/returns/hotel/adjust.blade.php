<div>
    <h5>Edit File return for {{ $return->financialMonth->name }}, {{ $return->financialMonth->year->code }}</h6>
        <hr>

        <div class="row">
            <div class="col-md-3 form-group">
                <label>Currency</label>
                <input type="text" disabled wire:model="taxTypeCurrency" required
                    class="form-control">
            </div>
            @if ($tax_type_id === 2)
                @foreach ($configs as $key => $config)
                    @if (in_array($config['col_type'], ['hotel_top']))
                        <div class="col-md-3 form-group">
                            <label>{{ $config['name'] }}</label>
                            <input type="number" maxlength="5" wire:model="configs.{{ $key }}.value" required
                                class="form-control @error("configs." . $key . ".value") is-invalid @enderror">
                                @error("configs." . $key . ".value")
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                        </div>
                    @endif
                @endforeach
            @endif
        </div>

        <table class="table table-bordered mb-4">
            {{-- Supplies of goods and services --}}
            <thead>
                <tr class="table-active">
                    <th>Supplies of goods & services / Mauzo ya bidhaa na/au huduma</th>
                    <th>Value (Excluding Tax) / Thamani bila ya kodi</th>
                    <th>Rate / Kiwango</th>
                    <th>Tax Amount (Kiasi cha Kodi)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($configs as $key => $config)
                    @if (($config['tax_type_id'] === $tax_type_id || $config['tax_type_id'] === null) &&
                        !in_array($config['col_type'], ['hotel_top', 'hotel_bottom']) &&
                        $config['heading_type'] == 'supplies')
                        <tr>
                            <td>{{ $config['name'] }}</td>
                            <td>
                                <input type="number" class="form-control @error("configs." . $key . ".value") is-invalid @enderror"
                                    wire:model="configs.{{ $key }}.value">
                                    @error("configs." . $key . ".value")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                            </td>
                            <td>
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
                                    {{ $config['rate'] }}%
                                @endif
                            </td>
                            <td>
                                @php
                                    echo number_format($this->singleVatCalculate($key), 2);
                                @endphp
                            </td>
                        </tr>
                    @endif

                @endforeach
            </tbody>
            <thead>
                <tr class="table-active">
                    <th>Purchases / Manunuzi</th>
                    <th>Value of Purchases / Thamani ya Manunuzi</th>
                    <th>Rate / Kiwango</th>
                    <th>Tax Amount (Kiasi cha Kodi)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($configs as $key => $config)
                    @if (in_array($config['col_type'], ['total']))
                        @if ($config['tax_type_id'] === $tax_type_id)
                            <tr>
                                <td colspan="3"><strong>{{ $config['name'] }}</strong></td>
                                <td>
                                    <strong>
                                        @php
                                        $result = $this->totalVatCalculate($config['formular'], $key);
                                        echo number_format($result, 2);
                                    @endphp
                                    </strong>
                                </td>
                            </tr>
                        @endif
                    @else
                        @if (($config['tax_type_id'] === $tax_type_id || $config['tax_type_id'] === null) &&
                            !in_array($config['col_type'], ['hotel_top', 'hotel_bottom']) &&
                            $config['heading_type'] == 'purchases')
                            <tr>
                                <td>{{ $config['name'] }}</td>
                                <td>
                                    @if ($config['code'] !== 'LW')
                                        @if ($config['value_calculated'])
                                            @php
                                                echo number_format($this->valueCalculated($key, $config['value_formular']));
                                            @endphp
                                        @else
                                            <input type="number"
                                                class="form-control @error('configs.' . $key . '.value') is-invalid @enderror"
                                                wire:model="configs.{{ $key }}.value">
                                            @error('configs.' . $key . '.value')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        @endif
                                    @endif
                                </td>
                                <td>
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
                                        {{ $config['rate'] }}%
                                    @endif
                                </td>
                                <td>
                                    @if ($config['code'] === 'LW')
                                        <input type="number" class="form-control @error("configs." . $key . ".value") is-invalid @enderror"
                                            wire:model="configs.{{ $key }}.value">
                                            @error("configs." . $key . ".value")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                            @php
                                                $this->singleVatCalculate($key)
                                            @endphp
                                    @else
                                        @php
                                            echo number_format($this->singleVatCalculate($key), 2);
                                        @endphp
                                    @endif

                                </td>
                            </tr>
                        @endif

                    @endif

                @endforeach
            </tbody>
        </table>

        @if ($tax_type_id === 2)
            <h6>Rate Charged per Room (Bed & Breakfast)</h6>
            <hr>
            <div class="row mt-2">
                @foreach ($configs as $key => $config)
                    @if (in_array($config['col_type'], ['hotel_bottom']))
                        <div class="col-md-3 form-group">
                            <label>{{ $config['name'] }}</label>
                            <input type="number" wire:model="configs.{{ $key }}.value"
                                required class="form-control @error("configs." . $key . ".value") is-invalid @enderror">
                                @error("configs." . $key . ".value")
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        <hr>
        <div class="col-md-12 text-right">
            <button type="button" class="btn btn-primary ml-2 px-5" wire:click='toggleSummary(true)' wire:loading.attr="disabled">
                Next</button>
        </div>

</div>
