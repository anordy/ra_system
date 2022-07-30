<div class="p-3">
    <table class="table table-bordered">
        <thead>
            <tr>
                <td>Supplies of Petroleum Products @Lts / Mauzo ya Mafuta @Lts</td>
                <td>Quality in Lts / Ujazo kwa Lts</td>
                <td>Rate / Kiwango</td>
                <td>Levy Amount / Kiasi cha Kodi</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($configs as $key => $config)
                @if (in_array($config['col_type'], ['total', 'subtotal']))
                    <tr>
                        <td colspan="3">{{ $config['name'] }}</td>
                        <td>
                            @php
                                echo $this->totalVatCalculate($config['formular']);
                            @endphp
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>{{ $config['name'] }}</td>
                        <td>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" wire:model="configs.{{ $key }}.value">
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
                                    {{ $config['rate'] }} USD
                                @endif
                            @elseif ($config['rate_type'] == 'percentage')
                                {{ $config['rate'] }}
                            @endif
                        </td>
                        <td>
                            @php
                                echo $this->singleVatCalculate($key);
                            @endphp
                        </td>
                    </tr>
                @endif

            @endforeach
        </tbody>
    </table>
</div>
