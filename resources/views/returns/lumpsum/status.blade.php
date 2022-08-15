@if ($row->status == 'complete')
    <span class="badge badge-success"
        style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 100%; padding:3%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        PAID
    </span>
@elseif ($row->status == 'control-number-generated')
    <span class="badge badge-warning "
        style="border-radius: 1rem; background: #d1dc3559; color: #474704; font-size: 85%; padding:3%">
        <i class="fas fa-clock"></i>
        Control Number Generated
    </span>
@else
    <span class="badge badge-warning "
        style="border-radius: 1rem; background: #d1dc3559; color: #474704; font-size: 85%; padding:3%">
        <i class="fas fa-clock"></i>
        {{ $row->status }}
    </span>
@endif
