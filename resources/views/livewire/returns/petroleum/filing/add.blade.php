<div class="card p-0 m-0">
    <div class="card-body">
        <table class="table table-bordered">
            <thead class="font-weight-bold">
                <tr>
                    <td>Supplies of Petroleum Products @Lts / Mauzo ya Mafuta @Lts</td>
                    <td>Quality in Lts / Ujazo kwa Lts</td>
                    <td>Rate / Kiwango</td>
                    <td>Levy Amount / Kiasi cha Kodi</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($configs as $key => $config)
                    @if ($config['col_type'] == 'heading')
                        <tr class="font-weight-bold">
                            @foreach ($config['headings'] as $heading)
                                <td>
                                    {{ $heading['name'] }}
                                </td>
                            @endforeach
                        </tr>
                    @elseif (in_array($config['col_type'], ['total', 'subtotal']))
                        <tr>
                            <td colspan="3">{{ $config['name'] }}</td>
                            <td>
                                @php
                                    echo number_format($this->totalVatCalculate($key, $config['formular']));
                                @endphp
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ $config['name'] }}</td>
                            <td>
                                @if ($config['value_calculated'])
                                    @php
                                        echo number_format($this->valueCalculated($key, $config['value_formular']));
                                    @endphp
                                @else
                                    <div class="input-group">
                                        <input type="number" class="form-control rounded"
                                            wire:model="configs.{{ $key }}.value">
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if ($config['rate_applicable'])
                                    @if ($config['value_calculated'])
                                        {{ $config['value_formular'] }}
                                    @else
                                        @if ($config['rate_type'] == 'fixed')
                                            @if ($config['currency'] == 'both')
                                                {{ $config['rate'] }} TZS <br>
                                                {{ $config['rate_usd'] }} USD
                                            @elseif ($config['currency'] == 'TZS')
                                                {{ $config['rate'] }} TZS
                                            @elseif ($config['currency'] == 'USD')
                                                {{ $config['rate'] }} USD
                                            @endif
                                        @elseif ($config['rate_type'] == 'percentage')
                                            {{ $config['rate'] }}
                                        @endif
                                    @endif
                                @endif

                            </td>
                            <td>
                                @if ($config['rate_applicable'])
                                    @php
                                        echo number_format($this->singleVatCalculate($key));
                                    @endphp
                                @endif
                            </td>
                        </tr>
                    @endif

                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex justify-content-end">
        <div>
            <button type="button" class="btn btn-info" wire:click='save'>Save</button>
            <button type="button" class="btn btn-primary" wire:click='submit'>Submit & Pay</button>
        </div>
    </div>
</div>
