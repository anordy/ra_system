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
        @if(count($extensions))
            @foreach ($extensions as $extension)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $extension->installment->installable->business->name }}</td>
                    <td>{{ $extension->installment->installable->taxType->name}}</td>
                    <td>{{ $extension->installment->installable->currency }}</td>
                    {{--                    <td>{{ $extension }}</td>--}}
                    <td>
                        @include('livewire.installment.includes.status')
                    </td>
                    <td>
                        <a href="{{ route('installment.extensions.show', ['id'=> encrypt($extension->id)]) }}" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-eye"></i> View
                        </a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" class="text-center py-3">
                    No extension(s).
                </td>
            </tr>
        @endif
        </tbody>
    </table>

</div>