<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <th scope="col">Site Plan Number (DP No.)</th>
                <th scope="col">Commence date</th>
                @foreach ($landLeases as $landLease)
                <tr>
                    <td>
                        {{ $landLease->dp_number }}
                    </td>
                    <td>
                        {{ date('d/m/Y', strtotime($landLease->commence_date)) }}
                    </td>
                </tr>
                @endforeach
               
            </table>
            <div>
                <p>
                    {{-- {!! $landLeases->links() !!} --}}
                </p>
            </div>
        </div>   
    </div>

</div>
