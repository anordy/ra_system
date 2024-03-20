@if ($row->status === 'complete')
<span class="badge badge-success py-1 px-2 green-status">
    Paid
</span>
@else
<span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #dc354559; color: #cf1c2d;; font-size: 85%">
    Not Paid
</span>
@endif