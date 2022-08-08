<div class="card p-0 m-0">
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>Excise Duty Paybale Services / Huduma Zinazotozwa Ushuru</td>
                    <td>Value ( Duty Exclusive ) / Thamani Bila ya Ushuru</td>
                    <td>Rate / Kiwango</td>
                    <td>Excise Duty Amount / Kiasi Cha Ushuru</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($configs as $key => $config)
                    @if (in_array($config['col_type'], ['total', 'subtotal']))
                        <tr>
                            <td colspan="3">{{ $config['name'] }}</td>
                            <td>
                                @php
                                    echo number_format($this->totalVatCalculate($config['formular']), 2);
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
                                        {{ $config['rate'] }} USD
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
    </div>

    <div class="card-footer d-flex justify-content-end">
        <a href="{{ route('returns.filing') }}" class="btn btn-danger" wire:click='save'>
            Cancel
        </a>
        <button type="button" class="btn btn-primary ml-2 px-5" wire:click='toggleSummary(true)'
            wire:loading.attr="disabled">
            Next
        </button>
    </div>
</div>
