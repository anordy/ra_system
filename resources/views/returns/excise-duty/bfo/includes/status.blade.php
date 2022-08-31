@if ($row->status == 'complete')
    <span class="p-2 badge badge-success"
        style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 100%; padding:3%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        PAID
    </span>
@elseif ($row->status == 'control-number-generated')
    <span class="p-2 badge badge-warning "
        style="border-radius: 1rem; background: #d4dc3559; color: #474704; font-size: 100%; padding:3%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Control Number Generated
    </span>
@elseif ($row->status == 'control-number-generating')
    <span class="p-2 badge badge-warning "
        style="border-radius: 1rem; background: #dcd43559; color: #474704; font-size: 80%; padding:3%">
        <i class="fas fa-clock mr-1 "></i>
        Control Number Generating
    </span>
@elseif ($row->status == 'paid-by-debt')
    <span class="p-2 badge badge-success "
        style="border-radius: 1rem; background: #46dc3559; color: #474704; font-size: 100%; padding:3%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        PAID BY DEBT
    </span>
@elseif ($row->status == 'control-number-generating-failed')
    <span class="p-2 badge badge-warning "
        style="border-radius: 1rem; background: #f40f0b59; color: #5e3e3e; font-size: 80%; padding:3%">
        <i class="fas fa-exclamation"> </i>
        Control Number Generation Failed
    </span>
@else
    <span class="p-2 badge badge-warning "
        style="border-radius: 1rem; background: #d1dc3559; color: #474704; font-size: 100%; padding:3%">
        {{ $row->status }}
    </span>
@endif
