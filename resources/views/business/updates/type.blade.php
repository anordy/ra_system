@if($value === 'business_information')
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        Business Information
    </span>
@elseif($value === 'responsible_person')
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        Responsible Person
    </span>
@elseif($value === 'bank_information')
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        Bank Information
    </span>
@elseif($value === 'business_attachments')
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        Business Attachments
    </span>
@elseif($value === 'hotel_information')
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        Hotel Information
    </span>
@else
    <span class="badge badge-danger py-1 px-2" style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%">
        Uncategorized
    </span>
@endif