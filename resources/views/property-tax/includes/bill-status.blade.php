@if($row->latestBill->status === \App\Enum\PaymentStatus::PAID)
    <a href="{{ route('bill.receipt', encrypt($row->latestBill->id)) }}" target="_blank" class="btn btn-outline-info btn-sm">
        <i class="bi bi-eye-fill mr-1"></i> Get Receipt
    </a>
@endif
