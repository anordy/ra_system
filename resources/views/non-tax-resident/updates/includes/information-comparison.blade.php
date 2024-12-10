<div class="mt-5 mx-4">
    <h5 class="mb-4">Business Information Comparison</h5>

    <!-- Business Info Table -->
    <div class="card mb-4">
        <div class="card-header">Business Info</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Field</th>
                    <th>Current Value</th>
                    <th>New Value</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @if($business->business_type == \App\Enum\NonTaxResident\NtrBusinessType::NON_RESIDENT)
                    @php
                        $fields = ['name' => 'Name', 'email' => 'Email', 'business_street' => 'Street', 'business_address' => 'Address', 'business_nature' => 'Nature of Business'];
                    @endphp
                @else
                    @php
                        $fields = ['name' => 'Name', 'email' => 'Email', 'business_street' => 'Street', 'business_address' => 'Address', 'business_nature' => 'Nature of Business', 'annual_revenue_threshold' => 'Annual Revenue Threshold', 'payment_gateway' => 'Payment Gateway'];
                    @endphp
                @endif

                @foreach($fields as $key => $label)
                    <tr>
                        <td>{{ $label }}</td>
                        <td>{{ $formattedInfo['current_business_info'][$key] ?? 'N/A' }}</td>
                        <td>{{ $formattedInfo['new_business_info'][$key] ?? 'N/A' }}</td>
                        <td>
                            @if(($formattedInfo['current_business_info'][$key] ?? '') === ($formattedInfo['new_business_info'][$key] ?? ''))
                                <span class="badge badge-success">Not Changed</span>
                            @else
                                <span class="badge badge-danger">Changed</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Business Socials Table -->
{{--    @if($formattedInfo['current_business_socials'] ?? [])--}}
{{--        <div class="card mb-4">--}}
{{--            <div class="card-header">Business Socials</div>--}}
{{--            <div class="card-body">--}}
{{--                <table class="table table-striped">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th>Platform</th>--}}
{{--                        <th>Current URL</th>--}}
{{--                        <th>New URL</th>--}}
{{--                        <th>Status</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    @foreach($formattedInfo['current_business_socials'] ?? [] as $index => $social)--}}
{{--                        <tr>--}}
{{--                            <td>{{ $social['name'] }}</td>--}}
{{--                            <td>{{ $social['url'] }}</td>--}}
{{--                            <td>{{ $formattedInfo['new_business_socials'][$index]['url'] ?? 'N/A' }}</td>--}}
{{--                            <td>--}}
{{--                                @if(($social['url'] ?? '') === ($formattedInfo['new_business_socials'][$index]['url'] ?? ''))--}}
{{--                                    <span class="badge badge-success">Not Changed</span>--}}
{{--                                @else--}}
{{--                                    <span class="badge badge-danger">Changed</span>--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}

    <!-- Business Contacts Table -->
{{--    @if($formattedInfo['current_business_contacts'])--}}
{{--        <div class="card mb-4">--}}
{{--            <div class="card-header">Business Contacts</div>--}}
{{--            <div class="card-body">--}}
{{--                <table class="table table-striped">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th>Contact Name</th>--}}
{{--                        <th>Current Mobile</th>--}}
{{--                        <th>New Mobile</th>--}}
{{--                        <th>Status</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    @foreach($formattedInfo['current_business_contacts'] ?? [] as $index => $contact)--}}
{{--                        <tr>--}}
{{--                            <td>{{ $contact['name'] }}</td>--}}
{{--                            <td>{{ $contact['mobile'] }}</td>--}}
{{--                            <td>{{ $formattedInfo['new_business_contacts'][$index]['mobile'] ?? 'N/A' }}</td>--}}
{{--                            <td>--}}
{{--                                @if(($contact['mobile'] ?? '') === ($formattedInfo['new_business_contacts'][$index]['mobile'] ?? ''))--}}
{{--                                    <span class="badge badge-success">Not Changed</span>--}}
{{--                                @else--}}
{{--                                    <span class="badge badge-danger">Changed</span>--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}

    <!-- Business Attachments Table -->
{{--    <div class="card mb-4">--}}
{{--        <div class="card-header">Business Attachments</div>--}}
{{--        <div class="card-body">--}}
{{--            <table class="table table-striped">--}}
{{--                <thead>--}}
{{--                <tr>--}}
{{--                    <th>Attachment</th>--}}
{{--                    <th>Current File</th>--}}
{{--                    <th>New File</th>--}}
{{--                    <th>Status</th>--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}
{{--                @foreach($formattedInfo['current_business_attachments'] ?? [] as $index => $attachment)--}}
{{--                    <tr>--}}
{{--                        <td>{{ $attachment['name'] ?? '' }}</td>--}}
{{--                        <td>--}}
{{--                            <div--}}
{{--                                    class="p-2 mb-3 d-flex rounded-sm align-items-center file-background">--}}
{{--                                <i class="bi bi-file-earmark-pdf-fill px-2 font-x-large"></i>--}}
{{--                                <a target="_blank"--}}
{{--                                   href="{{ route('tax-return-cancellation.file', encrypt($attachment['attachment_path'] ?? 'N/A')) }}"--}}
{{--                                   class="ml-1 font-weight-bold">--}}
{{--                                    View File--}}
{{--                                    <i class="bi bi-arrow-up-right-square ml-1"></i>--}}
{{--                                </a>--}}
{{--                            </div>--}}
{{--                        </td>--}}
{{--                        <td>--}}
{{--                            <div--}}
{{--                                    class="p-2 mb-3 d-flex rounded-sm align-items-center file-background">--}}
{{--                                <i class="bi bi-file-earmark-pdf-fill px-2 font-x-large"></i>--}}
{{--                                <a target="_blank"--}}
{{--                                   href="{{ route('tax-return-cancellation.file', encrypt($formattedInfo['new_business_attachments'][$index]['file'] ?? 'N/A')) }}"--}}
{{--                                   class="ml-1 font-weight-bold">--}}
{{--                                    View File--}}
{{--                                    <i class="bi bi-arrow-up-right-square ml-1"></i>--}}
{{--                                </a>--}}
{{--                            </div>--}}
{{--                        </td>--}}
{{--                        <td>--}}
{{--                            @if(($attachment['file'] ?? '') === ($formattedInfo['new_business_attachments'][$index]['file'] ?? ''))--}}
{{--                                <span class="badge badge-success">Not Changed</span>--}}
{{--                            @else--}}
{{--                                <span class="badge badge-danger">Changed</span>--}}
{{--                            @endif--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
{{--                </tbody>--}}
{{--            </table>--}}
{{--        </div>--}}
{{--    </div>--}}
</div>