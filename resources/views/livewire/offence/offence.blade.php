<div class="row">
    <div class="col-md-12">
        <a href="{{route('debts.offence.create')}}" class="btn btn-info float-right mb-2">
            <i class="fa fa-plus-circle"></i>
            New Offence
        </a>
    </div>
    <table class="table table-bordered table-sm mb-0">
        <thead>
        <tr>
            <th>#</th>
            <th>Debtor Name</th>
            <th>Amount</th>
            <th>Currency</th>
            <th>Tax Type</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>

        <tbody>
        @if(count($offences))
            @foreach ($offences as $offence)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $offence->name }}</td>
                    <td>{{ number_format($offence->amount, 2) }}</td>
                    <td>{{ $offence->currency }}</td>
                    <td>{{ $offence->taxTypes->name }}</td>
                    <td>
                        @include('livewire.offence.includes.status')
                    </td>
                    <td>
                        <a href="{{ route('debts.offence.show', ['offence'=> encrypt($offence->id)]) }}" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-eye"></i> View
                        </a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" class="text-center py-3">
                    No Offence(s).
                </td>
            </tr>
        @endif
        </tbody>
    </table>
    @include('livewire.offence.includes.models')

</div>