<div>

    <div class="card">
        <div class="card-header">
            Viable Tax Types Changes
        </div>

        <div class="card-body">
            <table class="table table-striped table-sm table-bordered">
                <thead>
                <th>Property</th>
                @if ($new_values)
                    <th>Old Data</th>
                    <th>New Data</th>
                    <th>Status</th>
                @else
                    <th>Data</th>
                @endif
                </thead>
                <tbody>
                <tr>
                    <th>Tax Types</th>
                    <td>
                        <p class="my-1">
                            @foreach($old_values ?? [] as $oldTax)
                                {{ $oldTax->name ?? 'N/A' }},
                            @endforeach
                        </p>
                    </td>
                    @if (isset($new_values->viable_tax_types))
                        <td>
                            @foreach(json_decode($new_values->viable_tax_types, TRUE) as $newTax)
                                {{ $newTax['name'] ?? 'N/A' }},
                            @endforeach
                        </td>
                        <td class="table-danger">CHANGED</td>
                    @endif
                </tr>


                </tbody>
            </table>
        </div>

    </div>

</div>