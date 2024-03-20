@if ($row->recon_status === \App\Enum\BankReconStatus::SUCCESS)
    <span class="badge badge-success py-1 px-2 green-status">
        Complete
    </span>
@elseif($row->recon_status === \App\Enum\BankReconStatus::AMOUNT_MISMATCH)
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #dc354559; color: #cf1c2d;; font-size: 85%">
        Amount Mismatch
    </span>
@elseif($row->recon_status === \App\Enum\BankReconStatus::NOT_FOUND)
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #dc354559; color: #cf1c2d;; font-size: 85%">
        Not Found
    </span>
@elseif($row->recon_status === \App\Enum\BankReconStatus::FAILED)
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #dc354559; color: #cf1c2d;; font-size: 85%">
        Failed
    </span>
@elseif($row->recon_status === \App\Enum\BankReconStatus::PENDING)
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: rgba(220,128,53,0.35); color: #cf871c;; font-size: 85%">
        Pending
    </span>
@endif