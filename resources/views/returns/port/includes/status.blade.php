@if ($row->status === \App\Enum\BillStatus::COMPLETE)
<span class="badge badge-success py-1 px-2">
    Paid
</span>
@else
<span class="badge badge-warning py-1 px-2">
    Not Paid
</span>
@endif