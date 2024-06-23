@if ($row->recon_status === \App\Enum\BankReconStatus::SUCCESS)
    <span class="badge badge-success py-1 px-2 green-status">
        Complete
    </span>
@elseif($row->recon_status === \App\Enum\BankReconStatus::AMOUNT_MISMATCH)
    <span class="badge badge-success py-1 px-2 danger-status">
        Amount Mismatch
    </span>
@elseif($row->recon_status === \App\Enum\BankReconStatus::NOT_FOUND)
    <span class="badge badge-success py-1 px-2 danger-status">
        Not Found
    </span>
@elseif($row->recon_status === \App\Enum\BankReconStatus::FAILED)
    <span class="badge badge-success py-1 px-2 danger-status">
        Failed
    </span>
@elseif($row->recon_status === \App\Enum\BankReconStatus::PENDING)
    <span class="badge badge-success py-1 px-2 pending-status">
        Pending
    </span>
@endif