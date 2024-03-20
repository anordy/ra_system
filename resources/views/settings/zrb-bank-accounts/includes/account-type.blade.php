@if ($value == true)
    <span class="badge badge-warning py-1 px-2">
        <i class="bi bi-arrow-down-up mr-1"></i>
        {{ \App\Models\ZrbBankAccount::TRANSFER_ACCOUNT  }}
    </span>
@elseif($value == false)
    <span class="badge badge-info py-1 px-2">
        <i class="bi bi-tag-fill mr-1"></i>
        {{ \App\Models\ZrbBankAccount::NORMAL_ACCOUNT }}
    </span>
@endif