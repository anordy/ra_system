@if($value === 'business_information')
    <span class="badge badge-success py-1 px-2 green-status">
        Business Information
    </span>
@elseif($value === 'responsible_person')
    <span class="badge badge-success py-1 px-2 green-status">
        Responsible Person
    </span>
@elseif($value === 'bank_information')
    <span class="badge badge-success py-1 px-2 green-status">
        Bank Information
    </span>
@elseif($value === 'business_attachments')
    <span class="badge badge-success py-1 px-2 green-status">
        Business Attachments
    </span>
@elseif($value === 'hotel_information')
    <span class="badge badge-success py-1 px-2 green-status">
        Hotel Information
    </span>
@elseif($value === 'transfer_ownership')
    <span class="badge badge-success py-1 px-2 green-status">
        Transfer Ownership
    </span>
@else
    <span class="badge badge-danger py-1 px-2 danger-status">
        Uncategorized
    </span>
@endif