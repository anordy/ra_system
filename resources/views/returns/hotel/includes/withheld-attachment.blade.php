<div class="card">
    <div class="card-body">
        <div class="card-header">WITHHELD ATTACHMENT</div>
        <table class="table table-bordered table-sm normal-text">
            <thead>
                <tr>
                    <th class="text-center">NO</th>
                    <th class="text-center">WITHHOLDING RECEIPT NO</th>
                    <th class="text-center">WITHHOLDING RECEIPT DATE</th>
                    <th class="text-center">VFMS RECEIPT NO</th>
                    <th class="text-center">VFMS RECEIPT DATE</th>
                    <th class="text-center">AGENT NAME</th>
                    <th class="text-center">AGENT NO</th>
                    <th class="text-center">NET AMOUNT</th>
                    <th class="text-center">TAX WITHHELD</th>
                </tr>
            </thead>

            <tbody>
                @if (count($return->withheld ?? []))
                @php
                    $netAmount = 0;
                    $taxWithheld = 0;
                @endphp
                    @foreach ($return->withheld as $index => $details)
                            <tr>
                                <th class="text-center">{{ $index+1 }}</th>
                                <td class="text-center">{{ $details['withholding_receipt_no'] }}</td>
                                <td class="text-center">{{ date('d-m-Y', strtotime($details['withholding_receipt_date'])) }}</td>
                                <td class="text-center">{{ $details['vfms_receipt_no'] }}</td>
                                <td class="text-center">{{ date('d-m-Y', strtotime($details['vfms_receipt_date'])) }}</td>
                                <td class="text-right">{{ $details['agent_name'] }}</td>
                                <td class="text-right">{{ $details['agent_no'] }}</td>
                                <td class="text-right">{{ number_format($details['net_amount'], 2) }}</td>
                                <td class="text-right">{{ number_format($details['tax_withheld'], 2) }}</td>
                            </tr>
                            @php
                                $netAmount += $details['net_amount'];
                                $taxWithheld += $details['tax_withheld'];
                            @endphp
                    @endforeach
                    <tr>
                        <td class="text-right"><strong>Total:</strong></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"><strong>{{ number_format($netAmount, 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($taxWithheld, 2) }}</strong>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="7" class="text-center py-3">
                            No data.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        @foreach($return->withheldCertificates as $certificate)
            <a class="file-item d-inline-flex pr-3 mr-2" target="_blank"
               href="{{ route('returns.stamp-duty.withheld-certificate', encrypt($certificate->id)) }}">
                <i class="bi bi-file-earmark-pdf-fill px-2 font-x-large"></i>
                <div class="ml-1 font-weight-bold">
                    Withheld Certificate {{ $loop->index + 1 }}
                </div>
                <i class="bi bi-arrow-up-right-square ml-2"></i>
            </a>
        @endforeach
    </div>
</div>
