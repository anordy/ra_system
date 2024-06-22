<div class="row">
    <table class="table table-bordered table-sm mb-0">
        <thead>
        <tr>
            <th>#</th>
            <th>Business Name</th>
            <th>Tax Type</th>
            <th>Currency</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>

        <tbody>
        @if(count($partials))
            @foreach ($partials as $partial)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $partial->installmentItem->installment->installable->business->name }}</td>
                    <td>{{ $partial->installmentItem->installment->installable->taxType->name }}</td>
                    <td>{{ $partial->installmentItem->installment->installable->currency  }}</td>

                    <td>
                           <span class="badge badge-info py-1 px-2">
                                <i class="bi bi-clock-history mr-1"></i>
                                {{ $partial->status }}
                            </span>
                    </td>
                    <td>
                        <a href="{{ route('installment.extensions.show.partial', ['id'=> encrypt($partial->id)]) }}" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-eye"></i> View
                        </a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" class="text-center py-3">
                    No Partial Payment(s).
                </td>
            </tr>
        @endif
        </tbody>
    </table>

</div>