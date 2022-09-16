@if($row->paid_at == null )
    <span class="badge badge-info">Not Paid</span>
@elseif($row->paid_at > $row->payment_due_date)
    <span class="badge badge-warning"> Late Payment</span>
@elseif($row->paid_at <= $row->payment_due_date)
    <span class="badge badge-success">In-Time Payment</span>
@endif