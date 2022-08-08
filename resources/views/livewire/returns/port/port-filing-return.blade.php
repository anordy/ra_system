<div>
    <h5>File return</h6>
        <hr>

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
                @foreach ($configs as $key => $config)
                    @if (in_array($config['col_type'], ['total', 'subtotal']))
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
                                    {{ $config['rate'] }} %
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
        <div class="col-md-12 text-right">
            <button type="button" class="btn btn-primary ml-2 px-5" wire:click='toggleSummary(true)'
                wire:loading.attr="disabled">
                Next</button>
        </div>

</div>
