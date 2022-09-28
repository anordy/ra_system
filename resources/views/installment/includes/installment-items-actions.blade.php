<a target="_blank" href="{{ route('bill.receipt', encrypt($row->bill->id)) }}" class="btn btn-primary btn-sm">
    <i class="bi bi-receipt-cutoff mr-1"></i> Download Receipt
</a>
<a target="_blank" href="{{ route('bill.invoice', encrypt($row->bill->id)) }}" class="btn btn-outline-primary btn-sm">
    <i class="bi bi-receipt mr-1"></i> Download Bill
</a>