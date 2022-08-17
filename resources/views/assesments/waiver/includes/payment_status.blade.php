<div>
    @if ($row->status != null && $row->status == \App\Enum\BillStatus::COMPLETE)
        <span class="badge badge-success py-1 px-2"
            style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
            <i class="bi bi-check-circle-fill mr-1"></i>
            Paid
        </span>
    @elseif($row->status == \App\Enum\BillStatus::CN_GENERATED)
        <span class="badge badge-danger py-1 px-2"
            style="border-radius: 1rem; background:  #72DC3559; color: #319e0a; font-size: 85%">
            <i class="bi bi-clock-history mr-1"></i>
            pending
        </span>
      
          @elseif($row->status == \App\Enum\BillStatus::CN_GENERATING)
        <span class="badge badge-danger py-1 px-2"
            style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
            <i class="bi bi-clock-history mr-1"></i>
            pending
        </span>
          @elseif($row->status == \App\Enum\BillStatus::PAID_PARTIALLY)
        <span class="badge badge-danger py-1 px-2"
            style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
            <i class="bi bi-clock-history mr-1"></i>
            pending
        </span>
          @elseif($row->status == \App\Enum\BillStatus::COMPLETED_PARTIALLY)
        <span class="badge badge-danger py-1 px-2"
            style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
            <i class="bi bi-clock-history mr-1"></i>
            pending
        </span>
    @endif

</div>
