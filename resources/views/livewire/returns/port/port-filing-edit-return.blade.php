<div>
    <h5>File return</h6>
        <hr>

        <table class="table table-bordered">
            <thead>
                <tr class="table-active">
                    <th>Service Rendered/Huduma Iliyotolewa</th>
                    <th>Number of Pax/(Idadi ya Abiria)</th>
                    <th>Rate /(Kiwango)</th>
                    <th>Levy Amount (Kiasi cha Kodi)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($configs as $key => $config)
                    @if ($config['col_type'] == 'subtotal')
                        <tr>
                            <td>{{ $config['name'] }}</td>
                            <td class="bg-secondary" colspan="1"></td>
                            <td class="bg-secondary" colspan="1"></td>
                            <td>
                                @php
                                    echo number_format($this->totalVatCalculate($key, $config['formular'], 2));
                                @endphp
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ $config['name'] }}</td>
                            <td>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control"
                                        wire:model="configs.{{ $key }}.value">
                                </div>
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
                                    {{ $config['rate'] }}
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
        </table>
        <div class="col-md-12 text-center">
            <div class="d-flex justify-content-end">
                <button wire:click="submit()" class="btn btn-info px-5 mt-3" type="button">
                    Submit
                </button>
                <div wire:loading.delay wire:target="submit()">
                    Processing Registration...
                </div>
            </div>
        </div>
</div>
