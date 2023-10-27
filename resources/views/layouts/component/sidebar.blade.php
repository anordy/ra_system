<nav id="sidebar" class="mb-3">
    <div class="sidebar-header text-center pb-0">
        <h3 class="mt-2 d-flex justify-content-center align-items-center">
            <div style="height: 38px; width: 38px; border-radius: 50%" class="mr-3">
                <img style="height: 38px; width: 38px; object-fit: contain;" src="{{ asset('images/logo.png') }}"
                     class="rounded-circle" height="38px">
            </div>
            ZIDRAS
        </h3>
    </div>

    <ul class="list-unstyled components">
        <li class="{{ request()->is('dashboard*') ? 'active' : '' }}">
            <a href="{{ route('home') }}">Home</a>
        </li>
        <li class="{{ request()->is('notifications*') ? 'active' : '' }}">
            <a href="{{ route('notifications') }}">Notifications
                @if (App\Models\Notification::whereNull('read_at')->where('seen', 0)->where('notifiable_type', get_class(auth()->user()))->where('notifiable_id', auth()->id())->count() > 0)
                    <span class="badge badge-light">
                        <strong>{{ App\Models\Notification::whereNull('read_at')->where('seen', 0)->where('notifiable_type', get_class(auth()->user()))->where('notifiable_id', auth()->id())->count() }}</strong></span>
                @endif
            </a>
        </li>

        @can('taxpayer-management')
            <li class="{{ request()->is('taxpayers*') || request()->is('kycs-amendment*') ? 'active' : '' }}">
                <a href="#taxpayersMenu" data-toggle="collapse"
                   aria-expanded="{{ request()->is('taxpayers*') || request()->is('kycs-amendment*') ? 'true' : 'false' }}"
                   class="dropdown-toggle">Taxpayers
                    Management</a>
                <ul class="collapse list-unstyled {{ request()->is('taxpayers*') || request()->is('kycs-amendment*') ? 'show' : '' }}"
                    id="taxpayersMenu">
                    @can('taxpayer_view')
                        <li class="{{ request()->is('taxpayers/taxpayer*') ? 'active' : '' }}">
                            <a href="{{ route('taxpayers.taxpayer.index') }}">Taxpayers</a>
                        </li>
                    @endcan
                    @can('taxpayer-amendment-requests-view')
                        <li class="{{ request()->is('taxpayers-amendment*') ? 'active' : '' }}">
                            <a href="{{ route('taxpayers-amendment.index') }}">Taxpayer Amendments</a>
                        </li>
                    @endcan
                    @can('kyc_view')
                        <li class="{{ request()->is('taxpayers/registrations*') ? 'active' : '' }}">
                            <a href="{{ route('taxpayers.registrations.index') }}">KYC</a>
                        </li>
                    @endcan
                    @can('all-kyc-amendment-requests-view')
                        <li class="{{ request()->is('kycs-amendment*') ? 'active' : '' }}">
                            <a href="{{ route('kycs-amendment.index') }}">KYC Amendments</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('business-management')
            <li class="{{ request()->is('business*') ? 'active' : '' }}">
                <a href="#businessMenu" data-toggle="collapse"
                   aria-expanded="{{ request()->is('business*') ? 'true' : 'false' }}" class="dropdown-toggle">Business
                    Management</a>
                <ul class="collapse list-unstyled {{ request()->is('business*') ? 'show' : '' }}" id="businessMenu">
                    @can('business-registration-view')
                        <li class="{{ request()->is('business/registrations*') ? 'active' : '' }}">
                            <a href="{{ route('business.registrations.index') }}">Registrations</a>
                        </li>
                    @endcan
                    @can('business-branches-view')
                        <li class="{{ request()->is('business/branches*') ? 'active' : '' }}">
                            <a href="{{ route('business.branches.index') }}">Branches</a>
                        </li>
                    @endcan
                    @can('de-registration-view')
                        <li class="{{ request()->is('business/deregistration*') ? 'active' : '' }}">
                            <a href="{{ route('business.deregistrations') }}">De-registrations</a>
                        </li>
                    @endcan
                    @can('temporary-closures-view')
                        <li class="{{ request()->is('business/closure*') ? 'active' : '' }}">
                            <a href="{{ route('business.closure') }}">Temporary Closures</a>
                        </li>
                    @endcan
                    @can('business-update-request-view')
                        <li class="{{ request()->is('business/updates*') ? 'active' : '' }}">
                            <a href="{{ route('business.updatesRequests') }}">Business Updates Requests</a>
                        </li>
                    @endcan
                    @can('business-update-request-view')
                        <li class="{{ request()->is('business/internal-info-change/index*') ? 'active' : '' }}">
                            <a href="{{ route('business.internal-info-change.index') }}">Internal Information Change</a>
                        </li>
                    @endcan
                    @can('taxtype-change-request-view')
                        <li class="{{ request()->is('business/change-taxtype*') ? 'active' : '' }}">
                            <a href="{{ route('business.taxTypeRequests') }}">Tax Type Changes Requests</a>
                        </li>
                    @endcan
                    @can('qualified-tax-types-view')
                        <li class="{{ request()->is('business/qualified-tax-types*') ? 'active' : '' }}">
                            <a href="{{ route('business.qualified-tax-types.index') }}">Qualified Tax Types</a>
                        </li>
                    @endcan
                    @can('upgraded-tax-types-view')
                        <li class="{{ request()->is('business/upgraded-tax-types*') ? 'active' : '' }}">
                            <a href="{{ route('business.upgraded-tax-types.index') }}">Upgraded Tax Types</a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endcan
        @can('tax-consultant')
            <li class="{{ request()->is('taxagents*') ? 'active' : '' }}">
                <a href="#taxagentSubmenu" data-toggle="collapse"
                   aria-expanded="{{ request()->is('taxagents*') ? 'true' : 'false' }}" class="dropdown-toggle">Tax
                    Consultants</a>
                <ul class="collapse list-unstyled {{ request()->is('taxagents*') ? 'show' : '' }}" id="taxagentSubmenu">
                    @can('tax-consultant-registration-view')
                        <li class="{{ request()->is('taxagents/requests') ? 'active' : '' }}">
                            <a href="{{ route('taxagents.requests') }}">Registration Requests</a>
                        </li>
                    @endcan
                    @can('active-tax-consultant-view')
                        <li class="{{ request()->is('taxagents/active*') ? 'active' : '' }}">
                            <a href="{{ route('taxagents.active') }}">Active Tax Consultants</a>
                        </li>
                    @endcan
                    @can('tax-consultant-renewal-requests-view')
                        <li class="{{ request()->is('taxagents/renew*') ? 'active' : '' }}">
                            <a href="{{ route('taxagents.renew') }}">Renewal Requests</a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endcan

            <li class="{{ request()->is('property-tax*') ? 'active' : '' }}">
                <a href="#propertyTaxMenu" data-toggle="collapse"
                   aria-expanded="{{ request()->is('property-tax*') ? 'true' : 'false' }}" class="dropdown-toggle">Property Tax
                    </a>
                <ul class="collapse list-unstyled {{ request()->is('property-tax*') ? 'show' : '' }}" id="propertyTaxMenu">
                        <li class="{{ request()->is('property-tax/condominium/registration') ? 'active' : '' }}">
                            <a href="{{ route('property-tax.condominium.registration') }}">Condominium Registration</a>
                        </li>
                    <li class="{{ request()->is('property-tax/condominium/index') ? 'active' : '' }}">
                        <a href="{{ route('property-tax.condominium.index') }}">Registered Condominiums</a>
                    </li>
                    <li class="{{ request()->is('property-tax/index') ? 'active' : '' }}">
                        <a href="{{ route('property-tax.index') }}">Properties Registrations</a>
                    </li>
{{--                    <li class="{{ request()->is('property-tax/next-bills') ? 'active' : '' }}">--}}
{{--                        <a href="{{ route('property-tax.next.bills') }}">Next Bills Preview</a>--}}
{{--                    </li>--}}
                </ul>
            </li>

        @can('withholding-agent')
            <li class="{{ request()->is('withholdingAgents*') ? 'active' : '' }}">
                <a href="#withholdingAgentsMenu" data-toggle="collapse"
                   aria-expanded="{{ request()->is('withholdingAgents*') ? 'true' : 'false' }}"
                   class="dropdown-toggle">Withholding Agents</a>
                <ul class="collapse list-unstyled {{ request()->is('withholdingAgents*') ? 'show' : '' }}"
                    id="withholdingAgentsMenu">
                    @can('withholding-agents-registration')
                        <li class="{{ request()->is('withholdingAgents/register*') ? 'active' : '' }}">
                            <a href="{{ route('withholdingAgents.register') }}">Registration</a>
                        </li>
                    @endcan
                    @can('withholding-agents-view')
                        <li class="{{ request()->is('withholdingAgents/list*') ? 'active' : '' }}">
                            <a href="{{ route('withholdingAgents.list') }}">Withholding Agents List</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('tax-return')
            <li class="{{ request()->is('e-filling*') ? 'active' : '' }}">
                <a href="#returnsSubmenu" data-toggle="collapse"
                   aria-expanded="{{ request()->is('e-filling*') ? 'true' : 'false' }}" class="dropdown-toggle">Tax
                    Returns</a>
                <ul class="collapse list-unstyled {{ request()->is('e-filling*') ? 'show' : '' }}" id="returnsSubmenu">
                    @can('return-hotel-levy-view')
                        <li class="{{ request()->is('e-filling/hotel*') ? 'active' : '' }}">
                            <a href="{{ route('returns.hotel.index') }}">Hotel Levy</a>
                        </li>
                    @endcan
                    @can('return-hotel-levy-view')
                        <li class="{{ request()->is('e-filling/airbnb*') ? 'active' : '' }}">
                            <a href="{{ route('returns.airbnb.index') }}">Hotel Airbnb</a>
                        </li>
                    @endcan
                    @can('return-tour-operation-view')
                        <li class="{{ request()->is('e-filling/tour*') ? 'active' : '' }}">
                            <a href="{{ route('returns.tour.index') }}">Tour Operation Levy</a>
                        </li>
                    @endcan
                    @can('return-restaurant-levy-view')
                        <li class="{{ request()->is('e-filling/restaurant*') ? 'active' : '' }}">
                            <a href="{{ route('returns.restaurant.index') }}">Restaurant Levy</a>
                        </li>
                    @endcan
                    @can('return-vat-return-view')
                        <li class="{{ request()->is('e-filling/vat*') ? 'active' : '' }}">
                            <a href="{{ route('returns.vat-return.index') }}">VAT Tax Returns</a>
                        </li>
                    @endcan
                    @can('return-airport-return-view')
                        <li class="{{ request()->is('e-filling/airport*') ? 'active' : '' }}">
                            <a href="{{ route('returns.airport.index') }}">AirPort Tax Returns</a>
                        </li>
                    @endcan
                    @can('return-seaport-return-view')
                        <li class="{{ request()->is('e-filling/seaport*') ? 'active' : '' }}">
                            <a href="{{ route('returns.seaport.index') }}">SeaPort Tax Returns</a>
                        </li>
                    @endcan
                    @can('return-bfo-excise-duty-return-view')
                        <li class="{{ request()->is('e-filling/bfo-excise-duty*') ? 'active' : '' }}">
                            <a href="{{ route('returns.bfo-excise-duty.index') }}">
                                Banks, Financial Institutions and Others Tax Returns
                            </a>
                        </li>
                    @endcan
                    @can('return-mno-excise-duty-return-view')
                        <li class="{{ request()->is('e-filling/excise-duty/mno*') ? 'active' : '' }}">
                            <a href="{{ route('returns.excise-duty.mno') }}">Mobile Network Operator Tax Returns</a>
                        </li>
                    @endcan
                    @can('return-electronic-money-transaction-return-view')
                        <li class="{{ request()->is('e-filling/em-transaction*') ? 'active' : '' }}">
                            <a href="{{ route('returns.em-transaction.index') }}">Electronic Money Transaction
                                Returns</a>
                        </li>
                    @endcan
                    @can('return-stamp-duty-return-view')
                        <li class="{{ request()->is('e-filling/stamp-duty*') ? 'active' : '' }}">
                            <a href="{{ route('returns.stamp-duty.index') }}">Stamp Duty Composition Tax Returns</a>
                        </li>
                    @endcan
                    @can('return-lump-sum-payment-return-view')
                        <li class="{{ request()->is('e-filling/lump-sum*') ? 'active' : '' }}">
                            <a href="{{ route('returns.lump-sum.index') }}">Stamp Duty Lumpsum Tax Returns</a>
                        </li>
                    @endcan
                    @can('return-mobile-money-transfer-view')
                        <li class="{{ request()->is('e-filling/mobile-money-transfer*') ? 'active' : '' }}">
                            <a href="{{ route('returns.mobile-money-transfer.index') }}">Mobile Money Transfer</a>

                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('petroleum-management')
            <li class="{{ request()->is('petroleum*') ? 'active' : '' }}">
                <a href="#petroleum" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Petroleum
                    Management</a>
                <ul class="collapse list-unstyled {{ request()->is('petroleum*') ? 'show' : '' }}" id="petroleum">
                    @can('certificate-of-quantity-view')
                        <li class="{{ request()->is('petroleum/certificateOfQuantity*') ? 'active' : '' }}">
                            <a href="{{ route('petroleum.certificateOfQuantity.index') }}">Certificate of Quantity</a>
                        </li>
                    @endcan
                    @can('return-petroleum-return-view')
                        <li class="{{ request()->is('petroleum/filling*') ? 'active' : '' }}">
                            <a href="{{ route('petroleum.filling.index') }}">Petroleum Tax Returns</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('return-verification')
            <li class="{{ request()->is('tax_verifications*') ? 'active' : '' }}">
                <a href="#tax_verifications" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    Returns Verifications
                </a>
                <ul class="collapse list-unstyled {{ request()->is('tax_verifications*') ? 'show' : '' }}"
                    id="tax_verifications">
                    <li class="{{ request()->is('tax_verifications/approvals*') ? 'active' : '' }}">
                        <a href="{{ route('tax_verifications.approvals.index') }}">Approvals</a>
                    </li>
                    <li class="{{ request()->is('tax_verifications/assessments*') ? 'active' : '' }}">
                        <a href="{{ route('tax_verifications.assessments.index') }}">Assessments</a>
                    </li>
                    <li class="{{ request()->is('tax_verifications/verified*') ? 'active' : '' }}">
                        <a href="{{ route('tax_verifications.verified.index') }}">Approved Returns</a>
                    </li>
                </ul>
            </li>
        @endcan
        @can('tax-returns-vetting')
            <li class="{{ request()->is('tax_vetting*') ? 'active' : '' }}">
                <a href="#tax_vetting" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    Returns Vetting
                </a>
                <ul class="collapse list-unstyled {{ request()->is('tax_vettings*') ? 'show' : '' }}"
                    id="tax_vetting">
                    <li class="{{ request()->is('tax_vettings/approvals*') ? 'active' : '' }}">
                        <a href="{{ route('tax_vettings.approvals') }}">Filed Returns</a>
                    </li>
                    <li class="{{ request()->is('tax_vettings/on-correction*') ? 'active' : '' }}">
                        <a href="{{ route('tax_vettings.on.correction') }}">Returns On Correction</a>
                    </li>
                    <li class="{{ request()->is('tax_vettings/corrected*') ? 'active' : '' }}">
                        <a href="{{ route('tax_vettings.corrected') }}">Corrected Returns</a>
                    </li>
                    <li class="{{ request()->is('tax_vettings/vetted*') ? 'active' : '' }}">
                        <a href="{{ route('tax_vettings.vetted') }}">Vetted Returns</a>
                    </li>
                </ul>
            </li>
        @endcan
        @can('tax-auditing')
            <li class="{{ request()->is('tax_auditing*') ? 'active' : '' }}">
                <a href="#tax_auditing" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    Tax Auditing
                </a>
                <ul class="collapse list-unstyled {{ request()->is('tax_auditing*') ? 'show' : '' }}" id="tax_auditing">
                    @can('tax-auditing-approved-view')
                        <li class="{{ request()->is('tax_auditing/approvals*') ? 'active' : '' }}">
                            <a href="{{ route('tax_auditing.approvals.index') }}">Approvals</a>
                        </li>
                    @endcan
                    @can('tax-auditing-approved-view')
                        <li class="{{ request()->is('tax_auditing/assessments*') ? 'active' : '' }}">
                            <a href="{{ route('tax_auditing.assessments.index') }}">Assessments</a>
                        </li>
                    @endcan
                    @can('tax-auditing-approved-view')
                        <li class="{{ request()->is('tax_auditing/verified*') ? 'active' : '' }}">
                            <a href="{{ route('tax_auditing.verified.index') }}">Approved Audits</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('tax-investigation')
            <li class="{{ request()->is('tax_investigation*') ? 'active' : '' }}">
                <a href="#tax_investigation" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    Tax Investigations
                </a>
                <ul class="collapse list-unstyled {{ request()->is('tax_investigation*') ? 'show' : '' }}"
                    id="tax_investigation">
                    @can('tax-investigation-approval-view')
                        <li class="{{ request()->is('tax_investigation/approvals*') ? 'active' : '' }}">
                            <a href="{{ route('tax_investigation.approvals.index') }}">Approvals</a>
                        </li>
                    @endcan
                    @can('tax-investigation-assessment-view')
                        <li class="{{ request()->is('tax_investigation/assessments*') ? 'active' : '' }}">
                            <a href="{{ route('tax_investigation.assessments.index') }}">Assessments</a>
                        </li>
                    @endcan
                    @can('tax-investigation-approved-view')
                        <li class="{{ request()->is('tax_investigation/verified*') ? 'active' : '' }}">
                            <a href="{{ route('tax_investigation.verified.index') }}">Approved Investigations</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('debt-management')
            <li class="{{ request()->is('debts*') ? 'active' : '' }}">
                <a href="#debtManagement" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Debt
                    Management</a>
                <ul class="collapse list-unstyled {{ request()->is('debts*') ? 'show' : '' }}" id="debtManagement">
                    @can('debt-management-debts-view')
                        <li class="{{ request()->is('debts/returns*') ? 'active' : '' }}">
                            <a href="{{ route('debts.returns.index') }}">Return Debts</a>
                        </li>
                    @endcan
                    @can('debt-management-assessment-debt-view')
                        <li class="{{ request()->is('debts/assessments*') ? 'active' : '' }}">
                            <a href="{{ route('debts.assessments.index') }}">Assessment Debts</a>
                        </li>
                    @endcan
                    @can('debt-management-waiver-debt-view')
                        <li class="{{ request()->is('debts/waiver*') ? 'active' : '' }}">
                            <a href="{{ route('debts.waivers.index') }}">Waiver Requests</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('tax-claim')
            <li class="{{ request()->is('tax-claims*') || request()->is('tax-credits*') ? 'active' : '' }}">
                <a href="#tax-claim" data-toggle="collapse"
                   aria-expanded="{{ request()->is('tax-claims*') || request()->is('tax-credits*') ? 'true' : 'false' }}"
                   class="dropdown-toggle">Tax Claims</a>
                <ul class="collapse list-unstyled {{ request()->is('tax-claims*') || request()->is('tax-credits*') ? 'show' : '' }}"
                    id="tax-claim">
                    @can('tax-claim-view')
                        <li class="{{ request()->is('tax-claims*') ? 'active' : '' }}">
                            <a href="{{ route('claims.index') }}">Claims</a>
                        </li>
                    @endcan
                    {{-- @can('tax-credit-view') --}}
                    {{-- <li class="{{ request()->is('tax-credits*') ? 'active' : '' }}"> --}}
                    {{-- <a href="{{ route('credits.index') }}">Credits (CBF)</a> --}}
                    {{-- </li> --}}
                    {{-- @endcan --}}
                </ul>
            </li>
        @endcan
        @can('payment-extension')
            <li class="{{ request()->is('extensions-e-filling*') ? 'active' : '' }}">
                <a href="#extension-menu" data-toggle="collapse"
                   aria-expanded="{{ request()->is('extensions-e-filling*') ? 'true' : 'false' }}"
                   class="dropdown-toggle">Payment Extensions</a>
                <ul class="collapse list-unstyled {{ request()->is('extensions-e-filling*') ? 'show' : '' }}"
                    id="extension-menu">
                    @can('payment-extension-view')
                        <li class="{{ request()->is('extensions-e-filling*') ? 'active' : '' }}">
                            <a href="{{ route('extension.index') }}">Extensions Requests</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('payment-installment-view')
            <li class="{{ request()->is('installments-e-filling*') ? 'active' : '' }}">
                <a href="#installment-menu" data-toggle="collapse"
                   aria-expanded="{{ request()->is('installments-e-filling*') ? 'true' : 'false' }}"
                   class="dropdown-toggle">Installments</a>
                <ul class="collapse list-unstyled {{ request()->is('installments-e-filling*') ? 'show' : '' }}"
                    id="installment-menu">
                    @can('payment-installment-view')
                        <li class="{{ request()->is('installments-e-filling') ? 'active' : '' }}">
                            <a href="{{ route('installment.index') }}">Installments</a>
                        </li>
                    @endcan
                    @can('payment-installment-request-view')
                        <li class="{{ request()->is('installments-e-filling/requests*') ? 'active' : '' }}">
                            <a href="{{ route('installment.requests.index') }}">Installment Requests</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('dispute-management')
            <li class="{{ request()->is('assesments*') ? 'active' : '' }}">
                <a href="#assesmentsSubmenu" data-toggle="collapse"
                   aria-expanded="{{ request()->is('assesments*') ? 'true' : 'false' }}"
                   class="dropdown-toggle">Disputes
                    Management</a>
                <ul class="collapse list-unstyled {{ request()->is('assesments*') ? 'show' : '' }}"
                    id="assesmentsSubmenu">
                    @can('dispute-waiver-view')
                        <li class="{{ request()->is('assesments/waiver/*') ? 'active' : '' }}">
                            <a href="{{ route('assesments.waiver.index') }}">Waiver</a>
                        </li>
                    @endcan
                    @can('dispute-objection-view')
                        <li class="{{ request()->is('assesments/objection*') ? 'active' : '' }}">
                            <a href="{{ route('assesments.objection.index') }}">Objection</a>
                        </li>
                    @endcan
                    @can('dispute-waiver-objection-view')
                        <li class="{{ request()->is('assesments/waiverobjection/*') ? 'active' : '' }}">
                            <a href="{{ route('assesments.waiverobjection.index') }}">Waiver & Objection</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan


        @can('tax-clearance-management')
            <li class="{{ request()->is('tax-clearance*') ? 'active' : '' }}">
                <a href="{{ route('tax-clearance.index') }}">
                    Tax Clearance Requests
                </a>
            </li>
        @endcan

        @can('relief-managements')
            <li class="{{ request()->is('reliefs*') ? 'active' : '' }}">
                <a href="#reliefs" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Reliefs
                    Management</a>
                <ul class="collapse list-unstyled {{ request()->is('reliefs*') ? 'show' : '' }}" id="reliefs">
                    @can('relief-ministries-view')
                        <li class="{{ request()->is('reliefs/ministries*') ? 'active' : '' }}">
                            <a href="{{ route('reliefs.ministries.index') }}">Ministries</a>
                        </li>
                    @endcan
                    @can('relief-sponsors-view')
                        <li class="{{ request()->is('reliefs/sponsors*') ? 'active' : '' }}">
                            <a href="{{ route('reliefs.sponsors.index') }}">Sponsors</a>
                        </li>
                    @endcan
                    @can('relief-projects-view')
                        <li class="{{ request()->is('reliefs/projects*') ? 'active' : '' }}">
                            <a href="{{ route('reliefs.projects.index') }}">Projects</a>
                        </li>
                    @endcan
                    @can('relief-registration-view')
                        <li class="{{ request()->is('reliefs/registrations*') ? 'active' : '' }}">
                            <a href="{{ route('reliefs.registrations.index') }}">Register Relief</a>
                        </li>
                    @endcan
                    @can('relief-applications-view')
                        <li class="{{ request()->is('reliefs/applications*') ? 'active' : '' }}">
                            <a href="{{ route('reliefs.applications.index') }}">Relief Applications</a>
                        </li>
                    @endcan
                    @can('relief-generate-report-view')
                        <li class="{{ request()->is('reliefs/generate-report*') ? 'active' : '' }}">
                            <a href="{{ route('reliefs.generate.report') }}">Generate Report</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('legal-cases')
            <li class="{{ request()->is('cases*') ? 'active' : '' }}">
                <a href="#lcmSubmenu" data-toggle="collapse"
                   aria-expanded="{{ request()->is('cases*') ? 'true' : 'false' }}" class="dropdown-toggle">Legal Cases
                    Management</a>
                <ul class="collapse list-unstyled {{ request()->is('cases*') ? 'show' : '' }}" id="lcmSubmenu">
                    @can('legal-cases-view')
                        <li class="{{ request()->is('cases') ? 'active' : '' }}">
                            <a href="{{ route('cases.index') }}">Cases</a>
                        </li>
                    @endcan
                    @can('legal-cases-appeal')
                        <li class="{{ request()->is('cases/appeals') ? 'active' : '' }}">
                            <a href="{{ route('cases.appeals') }}">Appeals</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('motor-vehicle-view')
            <li class="{{ request()->is('mvr*') ? 'active' : '' }}">
                <a href="#mvrSubmenu" data-toggle="collapse"
                   aria-expanded="{{ request()->is('mvr*') ? 'true' : 'false' }}" class="dropdown-toggle">Motor Vehicle
                    Registration</a>
                <ul class="collapse list-unstyled {{ request()->is('mvr*') ? 'show' : '' }}" id="mvrSubmenu">
                    @can('motor-vehicle-registration')
                        <li class="{{ request()->is('mvr/register') ? 'active' : '' }}">
                            <a href="{{ route('mvr.register') }}">Motor Vehicle Registration</a>
                        </li>
                    @endcan

                    @can('motor-vehicle-plate-number-printing')
                        <li class="{{ request()->is('mvr/plate-numbers') ? 'active' : '' }}">
                            <a href="{{ route('mvr.plate-numbers') }}">Plate Number Printing</a>
                        </li>
                    @endcan

                    @can('motor-vehicle-status-change-request')
                        <li class="{{ request()->is('mvr/reg-change-requests') ? 'active' : '' }}">
                            <a href="{{ route('mvr.reg-change-requests') }}">Status Change Requests</a>
                        </li>
                    @endcan

                    @can('motor-vehicle-transfer-ownership')
                        <li class="{{ request()->is('mvr/transfer-ownership*') ? 'active' : '' }}">
                            <a href="{{ route('mvr.transfer-ownership') }}">Transfer Ownership</a>
                        </li>
                    @endcan

                    @can('motor-vehicle-deregistration')
                        <li class="{{ request()->is('mvr/de-register-requests*') ? 'active' : '' }}">
                            <a href="{{ route('mvr.de-register-requests') }}">De-registration</a>
                        </li>
                    @endcan

                    @can('motor-vehicle-status-written-off')
                        <li class="{{ request()->is('mvr/written-off') ? 'active' : '' }}">
                            <a href="{{ route('mvr.written-off') }}">Written-off Vehicles</a>
                        </li>
                    @endcan

                    @can('motor-vehicle-status-registered')
                        <li class="{{ request()->is('mvr/registered') ? 'active' : '' }}">
                            <a href="{{ route('mvr.registered') }}">Registered Motor Vehicles</a>
                        </li>
                    @endcan

                    @can('motor-vehicle-status-transport-agent')
                        <li class="{{ request()->is('mvr/agent') ? 'active' : '' }}">
                            <a href="{{ route('mvr.agent') }}">Transport Agents</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endif
        @can('driver-licences-view')
            <li class="{{ request()->is('drivers-license*') || request()->is('rio*') ? 'active' : '' }}">
                <a href="#dlSubmenu" data-toggle="collapse"
                   aria-expanded="{{ request()->is('drivers-license*') || request()->is('rio*') ? 'true' : 'false' }}"
                   class="dropdown-toggle">Driver's Licenses</a>
                <ul class="collapse list-unstyled {{ request()->is('drivers-license*') || request()->is('rio*') ? 'show' : '' }}"
                    id="dlSubmenu">
                    @can('driver-licences-application')
                        <li
                                class="{{ request()->is('drivers-license/applications') || request()->is('drivers-license*') ? 'active' : '' }}">
                            <a href="{{ route('drivers-license.applications') }}">Driver's License Applications</a>
                        </li>
                    @endcan

                    @can('driver-licences-view')
                        <li
                                class="{{ request()->is('drivers-license/license*') || request()->is('drivers-license*') ? 'active' : '' }}">
                            <a href="{{ route('drivers-license.licenses') }}">Driver's Licenses</a>
                        </li>
                    @endcan

                    @can('driver-licences-road-inspection')
                        <li class="{{ request()->is('rio*') ? 'active' : '' }}">
                            <a href="{{ route('rio.register') }}">Road Inspection Offences</a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endif
        @can('land-lease-management')
            <li class="{{ request()->is('land-lease*') ? 'active' : '' }}">
                <a href="#landLeaseSubmenu" data-toggle="collapse"
                   aria-expanded="{{ request()->is('land-lease*') ? 'true' : 'false' }}" class="dropdown-toggle">Land
                    Lease</a>
                <ul class="collapse list-unstyled {{ request()->is('land-lease*') ? 'show' : '' }}"
                    id="landLeaseSubmenu">
                    <li class="{{ request()->is('land-lease/list*') ? 'active' : '' }}">
                        <a href="{{ route('land-lease.list') }}">Land Lease List</a>
                    </li>
                    @can('land-lease-generate-report')
                        <li class="{{ request()->is('land-lease/generate-report*') ? 'active' : '' }}">
                            <a href="{{ route('land-lease.generate.report') }}">General Report</a>
                        </li>
                        <li class="{{ request()->is('land-lease/payment-report*') ? 'active' : '' }}">
                            <a href="{{ route('land-lease.payment.report') }}">Payment Report</a>
                        </li>
                    @endcan
                    @can('land-lease-agent-view')
                        <li class="{{ request()->is('land-lease/agents*') ? 'active' : '' }}">
                            <a href="{{ route('land-lease.agents') }}">Land Lease Agents</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('managerial-report')
            <li class="{{ request()->is('reports*') ? 'active' : '' }}">
                <a href="#reportSubmenu" data-toggle="collapse"
                   aria-expanded="{{ request()->is('reports*') ? 'true' : 'false' }}" class="dropdown-toggle">
                    Managerial reports
                </a>
                <ul class="collapse list-unstyled {{ request()->is('reports*') ? 'show' : '' }}" id="reportSubmenu">
                    @can('managerial-report-view')
                        @can('managerial-return-report-view')
                            <li class="{{ request()->is('reports/returns*') ? 'active' : '' }}">
                                <a href="{{ route('reports.returns') }}">Return Reports</a>
                            </li>
                        @endcan
                        @can('managerial-departmental-report-view')
                            <li class="{{ request()->is('reports/departmental*') ? 'active' : '' }}">
                                <a href="{{ route('reports.departmental') }}">Departmental Reports</a>
                            </li>
                        @endcan
                        @can('managerial-assessment-report-view')
                            <li class="{{ request()->is('reports/assesments*') ? 'active' : '' }}">
                                <a href="{{ route('reports.assesments') }}">Assessment Reports</a>
                            </li>
                        @endcan
                        @can('managerial-dispute-report-view')
                            <li class="{{ request()->is('reports/disputes*') ? 'active' : '' }}">
                                <a href="{{ route('reports.disputes') }}">Dispute Reports</a>
                            </li>
                        @endcan
                        @can('managerial-business-report-view')
                            <li class="{{ request()->is('reports/business*') ? 'active' : '' }}">
                                <a href="{{ route('reports.business.init') }}">Registration Reports</a>
                            </li>
                        @endcan
                        @can('managerial-claim-report-view')
                            <li class="{{ request()->is('reports/claims*') ? 'active' : '' }}">
                                <a href="{{ route('reports.claims.init') }}">Claim Reports</a>
                            </li>
                        @endcan
                        @can('managerial-debt-report-view')
                            <li class="{{ request()->is('reports/debts*') ? 'active' : '' }}">
                                <a href="{{ route('reports.debts') }}">Debt Reports</a>
                            </li>
                        @endcan
                        @can('managerial-payment-report-view')
                            <li class="{{ request()->is('reports/payments*') ? 'active' : '' }}">
                                <a href="{{ route('reports.payments') }}">Payment Reports</a>
                            </li>
                        @endcan
                    @endcan

                </ul>
            </li>
        @endcan

        {{-- @can('managerial-report-view')
            <li class="{{ request()->is('queries*') ? 'active' : '' }}">
                <a href="#queriesSubmenu" data-toggle="collapse"
                   aria-expanded="{{ request()->is('queries*') ? 'true' : 'false' }}" class="dropdown-toggle">
                    Return Queries
                </a>
                <ul class="collapse list-unstyled {{ request()->is('queries*') ? 'show' : '' }}" id="queriesSubmenu">
                    <li class="{{ request()->is('queries/sales-purchases*') ? 'active' : '' }}">
                        <a href="{{ route('queries.sales-purchases') }}">Sales Vs Purchases</a>
                    </li>

                    <li class="{{ request()->is('queries/all-credit-returns*') ? 'active' : '' }}">
                        <a href="{{ route('queries.all-credit-returns') }}">All Credit Returns</a>
                    </li>
                </ul>
            </li>
        @endcan --}}

        @can('manage-payment-management')
            <li class="{{ request()->is('payments*') ? 'active' : '' }}">
                <a href="#payments" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    Manage Payments
                </a>
                <ul class="collapse list-unstyled {{ request()->is('payments*') ? 'show' : '' }}" id="payments">
                    @can('manage-payments-view')
                        <li class="{{ request()->is('payments/daily-payments*') ? 'active' : '' }}">
                            <a href="{{ route('payments.daily-payments.index') }}">Daily Payments</a>
                        </li>

                        <li class="{{ request()->is('payments/pending*') ? 'active' : '' }}">
                            <a href="{{ route('payments.pending') }}">Pending Payments</a>
                        </li>

                        <li class="{{ request()->is('payments/completed*') ? 'active' : '' }}">
                            <a href="{{ route('payments.complete') }}">Completed Payments</a>
                        </li>

                        <li class="{{ request()->is('payments/cancelled*') ? 'active' : '' }}">
                            <a href="{{ route('payments.cancelled') }}">Cancelled Payments</a>
                        </li>

                        <li class="{{ request()->is('payments/failed*') ? 'active' : '' }}">
                            <a href="{{ route('payments.failed') }}">Failed Payments</a>
                        </li>

                        <li class="{{ request()->is('payments/recon-enquire*') ? 'active' : '' }}">
                            <a href="{{ route('payments.recon.enquire') }}">Reconciliations</a>
                        </li>

                        <li class="{{ request()->is('payments/bank-recon*') ? 'active' : '' }}">
                            <a href="{{ route('payments.bank-recon.index') }}">Bank Reconciliations</a>
                        </li>

                        <li class="{{ request()->is('payments/missing-bank-recon*') ? 'active' : '' }}">
                            <a href="{{ route('payments.bank-recon.missing') }}">Missing Bank Recons</a>
                        </li>

                        <li class="{{ request()->is('payments/recon-reports/index*') ? 'active' : '' }}">
                            <a href="{{ route('payments.recon-reports.index') }}">Reconciliations Report</a>
                        </li>

                        <li class="{{ request()->is('payments/ega-charges*') ? 'active' : '' }}">
                            <a href="{{ route('payments.ega-charges.index') }}">eGA Charges</a>
                        </li>
                        <li class="{{ request()->is('payments/departmental-reports*') ? 'active' : '' }}">
                            <a href="{{ route('payments.departmental-reports.index') }}">Departmental Reports</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('finance-management')
            <li class="{{ request()->is('finance*') ? 'active' : '' }}">
                <a href="#finance" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    Finance Management
                </a>
                <ul class="collapse list-unstyled {{ request()->is('finance*') ? 'show' : '' }}" id="finance">
                    @can('view-taxpayer-ledgers')
                        <li class="{{ request()->is('finance/taxpayer/ledger*') ? 'active' : '' }}">
                            <a href="{{ route('finance.taxpayer.ledgers') }}">Taxpayer Ledgers</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('tra-information')
            <li class="{{ request()->is('tra*') ? 'active' : '' }}">
                <a href="#tra" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    TRA Information
                </a>
                <ul class="collapse list-unstyled {{ request()->is('tra*') ? 'show' : '' }}" id="tra">
                    @can('tra-information-view-tin')
                        <li class="{{ request()->is('tra/tins*') ? 'active' : '' }}">
                            <a href="{{ route('tra.tins') }}">TINs Information</a>
                        </li>
                    @endcan

                    @can('tra-information-view-chassis-number')
                        <li class="{{ request()->is('tra/chassis*') ? 'active' : '' }}">
                            <a href="{{ route('tra.chassis') }}">Chassis Numbers</a>
                        </li>
                    @endcan

                    @can('tra-information-view-exited-good')
                        <li class="{{ request()->is('tra/goods*') ? 'active' : '' }}">
                            <a href="{{ route('tra.goods') }}">Exited Goods</a>
                        </li>
                    @endcan

                    @can('tra-information-view-efdms-receipt')
                        <li class="{{ request()->is('tra/receipts*') ? 'active' : '' }}">
                            <a href="{{ route('tra.receipts') }}">EFDMS Receipts</a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endcan


        @can('setting')
            <li class="{{ request()->is('settings*') ? 'active' : '' }}">
                <a href="#settings" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Settings</a>
                <ul class="collapse list-unstyled {{ request()->is('settings*') ? 'show' : '' }}" id="settings">
                    @can('setting-user-view')
                        <li class="{{ request()->is('settings/users*') ? 'active' : '' }}">
                            <a href="{{ route('settings.users.index') }}">Users</a>
                        </li>
                    @endcan
                    @can('setting-role-view')
                        <li class="{{ request()->is('settings/roles*') ? 'active' : '' }}">
                            <a href="{{ route('settings.roles.index') }}">Roles</a>
                        </li>
                    @endcan
                    @can('setting-country-view')
                        <li class="{{ request()->is('settings/country*') ? 'active' : '' }}">
                            <a href="{{ route('settings.country.index') }}">Countries</a>
                        </li>
                    @endcan
                    @can('setting-region-view')
                        <li class="{{ request()->is('settings/region*') ? 'active' : '' }}">
                            <a href="{{ route('settings.region.index') }}">Region</a>
                        </li>
                    @endcan
                    @can('setting-district-view')
                        <li class="{{ request()->is('settings/district*') ? 'active' : '' }}">
                            <a href="{{ route('settings.district.index') }}">District</a>
                        </li>
                    @endcan
                    @can('setting-ward-view')
                        <li class="{{ request()->is('settings/ward*') ? 'active' : '' }}">
                            <a href="{{ route('settings.ward.index') }}">Ward</a>
                        </li>
                    @endcan
                    @can('setting-street-view')
                        <li class="{{ request()->is('settings/street*') ? 'active' : '' }}">
                            <a href="{{ route('settings.street.index') }}">Streets</a>
                        </li>
                    @endcan
                    @can('setting-bank-view')
                        <li class="{{ request()->is('settings/banks*') ? 'active' : '' }}">
                            <a href="{{ route('settings.banks.index') }}">Banks</a>
                        </li>
                    @endcan
                    @can('setting-exchange-rate-view')
                        <li class="{{ request()->is('settings/exchange-rate*') ? 'active' : '' }}">
                            <a href="{{ route('settings.exchange-rate.index') }}">Exchange Rate</a>
                        </li>
                    @endcan
                    @can('setting-interest-rate-view')
                        <li class="{{ request()->is('settings/interest-rates*') ? 'active' : '' }}">
                            <a href="{{ route('settings.interest-rates.index') }}">Interest Rate</a>
                        </li>
                    @endcan
                    @can('setting-penalty-rate-view')
                        <li class="{{ request()->is('settings/penalty-rates*') ? 'active' : '' }}">
                            <a href="{{ route('settings.penalty-rates.index') }}">Penalty Rate</a>
                        </li>
                    @endcan
                    @can('setting-education-level-view')
                        <li class="{{ request()->is('settings/education-level*') ? 'active' : '' }}">
                            <a href="{{ route('settings.education-level.index') }}">Education Level</a>
                        </li>
                    @endcan
                    @can('setting-business-categories-view')
                        <li class="{{ request()->is('settings/business-categories*') ? 'active' : '' }}">
                            <a href="{{ route('settings.business-categories.index') }}">Business categories</a>
                        </li>
                    @endcan
                    @can('setting-tax-type-view')
                        <li class="{{ request()->is('settings/taxtypes*') ? 'active' : '' }}">
                            <a href="{{ route('settings.taxtypes.index') }}">Tax Types</a>
                        </li>
                    @endcan
                    @can('setting-tax-type-view')
                        <li class="{{ request()->is('settings/taxtypes*') ? 'active' : '' }}">
                            <a href="{{ route('settings.subvat.taxtypes') }}">VAT Tax Types</a>
                        </li>
                    @endcan
                    @can('setting-isic-level-one-view')
                        <li class="{{ request()->is('settings/isic1*') ? 'active' : '' }}">
                            <a href="{{ route('settings.isic1.index') }}">ISIC Level 1</a>
                        </li>
                    @endcan
                    @can('setting-isic-level-two-view')
                        <li class="{{ request()->is('settings/isic2*') ? 'active' : '' }}">
                            <a href="{{ route('settings.isic2.index') }}">ISIC Level 2</a>
                        </li>
                    @endcan
                    @can('setting-isic-level-three-view')
                        <li class="{{ request()->is('settings/isic3*') ? 'active' : '' }}">
                            <a href="{{ route('settings.isic3.index') }}">ISIC Level 3</a>
                        </li>
                    @endcan
                    @can('setting-isic-level-four-view')
                        <li class="{{ request()->is('settings/isic4*') ? 'active' : '' }}">
                            <a href="{{ route('settings.isic4.index') }}">ISIC Level 4</a>
                        </li>
                    @endcan
                    @can('setting-country-view')
                        <li class="{{ request()->is('settings/business-files*') ? 'active' : '' }}">
                            <a href="{{ route('settings.business-files.index') }}">Business Files</a>
                        </li>
                    @endcan
                    @can('setting-region-view')
                        <li class="{{ request()->is('settings/tax-regions*') ? 'active' : '' }}">
                            <a href="{{ route('settings.tax-regions.index') }}">Tax Regions</a>
                        </li>
                    @endcan
                    @can('setting-mvr-plate-size-view')
                        <li class="{{ request()->is('settings/mvr-generic/MvrPlateSize') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'MvrPlateSize') }}">Motor Vehicle Plate
                                Size</a>
                        </li>
                    @endcan
                    @can('setting-mvr-fee-view')
                        <li class="{{ request()->is('settings/mvr-generic/MvrFee') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'MvrFee') }}">Motor Vehicle Fees</a>
                        </li>
                    @endcan
                    @can('setting-mvr-deregistration-reason-view')
                        <li class="{{ request()->is('settings/mvr-generic/MvrDeRegistrationReason') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'MvrDeRegistrationReason') }}">De
                                Registration
                                Reasons</a>
                        </li>
                    @endcan
                    @can('setting-mvr-ownership-transfer-reason-view')
                        <li class="{{ request()->is('settings/mvr-generic/MvrOwnershipTransferReason') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'MvrOwnershipTransferReason') }}">Transfer
                                Reasons</a>
                        </li>
                    @endcan
                    @can('setting-mvr-transfer-category-view')
                        <li class="{{ request()->is('settings/mvr-generic/MvrTransferCategory') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'MvrTransferCategory') }}">Transfer
                                Categories</a>
                        </li>
                    @endcan
                    @can('setting-mvr-transfer-fee-view')
                        <li class="{{ request()->is('settings/mvr-generic/MvrTransferFee') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'MvrTransferFee') }}">Transfer Fees</a>
                        </li>
                    @endcan

                    @can('setting-dl-class-view')
                        <li class="{{ request()->is('settings/mvr-generic/DlLicenseClass') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'DlLicenseClass') }}">Driver's License
                                Classes</a>
                        </li>
                    @endcan

                    @can('setting-dl-duration-view')
                        <li class="{{ request()->is('settings/mvr-generic/DlLicenseDuration') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'DlLicenseDuration') }}">Driver's License
                                Duration</a>
                        </li>
                    @endcan

                    @can('setting-dl-blood-group-view')
                        <li class="{{ request()->is('settings/mvr-generic/DlBloodGroup') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'DlBloodGroup') }}">Blood Groups</a>
                        </li>
                    @endcan

                    @can('setting-dl-fee-view')
                        <li class="{{ request()->is('settings/mvr-generic/DlFee') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'DlFee') }}">Driver's License Fees</a>
                        </li>
                    @endcan

                    @can('setting-case-stage-view')
                        <li class="{{ request()->is('settings/mvr-generic/CaseStage') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'CaseStage') }}">Case Stages</a>
                        </li>
                    @endcan

                    @can('setting-case-outcome-view')
                        <li class="{{ request()->is('settings/mvr-generic/CaseOutcome') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'CaseOutcome') }}">Case Outcomes</a>
                        </li>
                    @endcan

                    @can('setting-case-decision-view')
                        <li class="{{ request()->is('settings/mvr-generic/CaseDecision') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'CaseDecision') }}">Case Decision</a>
                        </li>
                    @endcan

                    @can('setting-court-level-view')
                        <li class="{{ request()->is('settings/mvr-generic/CourtLevel') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'CourtLevel') }}">Court Levels</a>
                        </li>
                    @endcan

                    @can('setting-financial-year-view')
                        <li class="{{ request()->is('settings/financial-years') ? 'active' : '' }}">
                            <a href="{{ route('settings.financial-years') }}">Financial Years</a>
                        </li>
                    @endcan

                    @can('setting-financial-month-view')
                        <li class="{{ request()->is('settings/financial-months') ? 'active' : '' }}">
                            <a href="{{ route('settings.financial-months') }}">Financial Months</a>
                        </li>
                    @endcan

                    @can('setting-return-configuration-view')
                        <li class="{{ request()->is('settings/return-config/*') ? 'active' : '' }}">
                            <a href="{{ route('settings.return-config.index') }}">Return Configurations</a>
                        </li>
                    @endcan

                    @can('setting-transaction-fees-view')
                        <li class="{{ request()->is('settings/return-config/*') ? 'active' : '' }}">
                            <a href="{{ route('settings.transaction-fees.index') }}">Transaction Fees</a>
                        </li>
                    @endcan

                    @can('tax-consultant-fee-configuration-view')
                        <li class="{{ request()->is('settings/tax-consultant-duration*') ? 'active' : '' }}">
                            <a href="{{ route('settings.tax-consultant-duration') }}">Tax Consultant Duration</a>
                        </li>
                    @endcan

                    @can('setting-approval-level')
                        <li class="{{ request()->is('settings/approval-levels/*') ? 'active' : '' }}">
                            <a href="{{ route('settings.approval-levels.index') }}">Approval Levels</a>
                        </li>
                    @endcan
                    @can('setting-system-category-view')
                        <li class="{{ request()->is('settings/setting-system-categories*') ? 'active' : '' }}">
                            <a href="{{ route('settings.setting-system-categories.view') }}">System Setting
                                Categories</a>
                        </li>
                    @endcan
                    @can('system-setting-view')
                        <li class="{{ request()->is('settings/system-settings*') ? 'active' : '' }}">
                            <a href="{{ route('settings.system-settings.view') }}">System Settings</a>
                        </li>
                    @endcan
                    @can('zrb-bank-account-view')
                        <li class="{{ request()->is('settings/zrb-bank-accounts*') ? 'active' : '' }}">
                            <a href="{{ route('settings.zrb-bank-accounts.index') }}">ZRA Bank Accounts</a>
                        </li>
                    @endcan

                    @can('setting-api-user-view')
                        <li class="{{ request()->is('settings/api-users*') ? 'active' : '' }}">
                            <a href="{{ route('settings.api-users.index') }}">API User</a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endcan

        @can('system')
            <li class="{{ request()->is('system*') ? 'active' : '' }}">
                <a href="#system" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">System</a>
                <ul class="collapse list-unstyled {{ request()->is('system*') ? 'show' : '' }}" id="system">
                    @can('system-audit-trail-view')
                        <li class="{{ request()->is('system/audits*') ? 'active' : '' }}">
                            <a href="{{ route('system.audits.index') }}">Audit Trail</a>
                        </li>
                    @endcan
                    @can('system-workflow-view')
                        <li class="{{ request()->is('system/workflow*') ? 'active' : '' }}">
                            <a href="{{ route('system.workflow.index') }}">Workflow Configure</a>
                        </li>
                    @endcan
                    @can('setting-dual-control-activities-view')
                        <li class="{{ request()->is('system/dual-control-activities/*') ? 'active' : '' }}">
                            <a href="{{ route('system.dual-control-activities.index') }}">Dual Control Activities</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        <li class="{{ request()->is('account*') ? 'active' : '' }}">
            <a href="#accountMenu" data-toggle="collapse"
               aria-expanded="{{ request()->is('account*') ? 'true' : 'false' }}"
               class="dropdown-toggle">{{ __("Account") }}</a>
            <ul class="collapse list-unstyled {{ request()->is('account*') ? 'show' : '' }}" id="accountMenu">
                <li class="{{ request()->is('account') ? 'active' : '' }}">
                    <a href="{{ route('account') }}">{{ __("Account Details") }}</a>
                </li>
                {{-- <li class="{{ request()->is('account/security-questions') ? 'active' : '' }}">
                    <a href="{{ route('account.security-questions') }}">{{ __("Security Questions") }}</a>
                </li> --}}
                <li class="{{ request()->is('account/security-questions') ? 'active' : '' }}">
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __("Log out") }}
                    </a>
                </li>
            </ul>
        </li>
    </ul>

    <div class="profile d-flex justify-content-between align-items-center">
        <a href="{{ route('account') }}" class="d-flex align-items-center justify-content-between">
            <div>
                <i class="far fa-2x fa-user-circle"></i>
            </div>
            <div class="pl-2">
                <div>{{ auth()->user()->fullname() }}</div>
                <div>{{ auth()->user()->role->name ?? '' }}</div>
            </div>
        </a>

        <div class="pr-1">
            <a class="text-white" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-sign-out-alt"></i>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</nav>
