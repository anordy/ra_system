@if ($value == true)
    <span class="badge badge-success py-1 px-2"
      style="border-radius: 1rem; background: #bfdbfe; color: #1d4ed8; font-size: 85%">
        <i class="bi bi-arrow-down-up mr-1"></i>
        {{ \App\Models\ZrbBankAccount::TRANSFER_ACCOUNT  }}
    </span>
@elseif($value == false)
    <span class="badge badge-success py-1 px-2"
      style="border-radius: 1rem; background: #d4d4d8; color: #374151; font-size: 85%">
        <i class="bi bi-tag-fill mr-1"></i>
        {{ \App\Models\ZrbBankAccount::NORMAL_ACCOUNT }}
    </span>
@endif