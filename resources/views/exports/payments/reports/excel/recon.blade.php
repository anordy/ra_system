<html>
<table style="border-collapse:collapse;">
    <thead>
        <tr>
            <th style="text-align:center;" colspan="7" height="70px">
                <strong>ZANZIBAR Revenue Authority</strong><br>
                
                <strong>{{ $title }}</strong><br>
            
                <strong>Total Number of Records: {{ $records->count() }} </strong>
            </th>
        </tr>
    </thead>
</table>

<table class="table">
    <thead class="tableHead">
    <tr>
        <th style="text-align:center; border-collapse:collapse;border: 1px solid black;">
            <strong>S/N</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Control number</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Currency</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Bill Amount</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Paid Amount</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>PSP Name</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Business Name</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>ZanMalipo Reconciliation Status</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Bank Reconciliation Status</strong>
        </th>
        <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
            <strong>Created At</strong>
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach ($records as $index => $record)
        <tr>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $index + 1 }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->control_number ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->currency ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ number_format($record->amount,2) ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ number_format($record->paid_amount,2) ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->payment->psp_name ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->billable->business->name ?? '-' }}
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                @if ($record->zm_recon_status == 'pending')
                    NOT RECONCILED
                @elseif($record->zm_recon_status == 'success')
                    RECONCILED
                @else
                    {{ strtoupper($record->zm_recon_status ?? '-') ?? '-' }}
                @endif
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                @if ($record->bank_recon_status == 'pending')
                    NOT RECONCILED
                @elseif($record->zm_recon_status == 'success')
                    RECONCILED
                @else
                    {{ strtoupper($record->bank_recon_status) }}
                @endif
            </td>
            <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                {{ $record->created_at ?? '-' }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

</html>