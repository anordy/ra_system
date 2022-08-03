@if($row->status == 'submited')
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Submitted
    </span>
@else
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #35dcb5; color: #0a9e99; font-size: 85%">
        <i class="bi bi bi-x-circle-fill mr-1"></i>
        Draft
    </span>
@endif