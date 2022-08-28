<nav id="sidebar">
    <div class="sidebar-header text-center pb-0">
        <h3 class="mt-2"><i class="bi bi-card-heading mr-2"></i> ZITMS</h3>
    </div>

    <ul class="list-unstyled components">
        <li class="{{ request()->is('dashboard*') ? 'active' : '' }}">
            <a href="{{ route('home') }}">Home</a>
        </li>
        <li class="{{ request()->is('notifications*') ? 'active' : '' }}">
            <a href="{{ route('notifications') }}">Notifications
                @if (auth()->user()->unreadNotifications->count() > 0)
                <span class="badge badge-light">
                    <strong>{{ auth()->user()->unreadNotifications->count() }}</strong></span>
                @endif
            </a>
        </li>

        @can('taxpayer-management')
            <li class="{{ request()->is('taxpayers*') ? 'active' : '' }}">
                <a href="#taxpayersMenu" data-toggle="collapse"
                    aria-expanded="{{ request()->is('taxpayers*') ? 'true' : 'false' }}" class="dropdown-toggle">Taxpayers
                    Management</a>
                <ul class="collapse list-unstyled {{ request()->is('taxpayers*') ? 'show' : '' }}" id="taxpayersMenu">
                    @can('taxpayer_view')
                        <li class="{{ request()->is('taxpayers/taxpayer*') ? 'active' : '' }}">
                            <a href="{{ route('taxpayers.taxpayer.index') }}">Taxpayers</a>
                        </li>
                    @endcan
                    @can('kyc_view')
                        <li class="{{ request()->is('taxpayers/registrations*') ? 'active' : '' }}">
                            <a href="{{ route('taxpayers.registrations.index') }}">KYC</a>
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
                        <li class="{{ request()->is('business/deregistrations*') ? 'active' : '' }}">
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
                    @can('taxtype-change-request-view')
                        <li class="{{ request()->is('business/change-taxtype*') ? 'active' : '' }}">
                            <a href="{{ route('business.taxTypeRequests') }}">Tax Type Changes Requests</a>
                        </li>
                    @endcan
                    @can('qualified-tax-types-upgrade-view')
                        <li class="{{ request()->is('business/upgrade-tax-types*') ? 'active' : '' }}">
                            <a href="{{ route('business.upgrade-tax-types.index') }}">Qualified Tax Types Upgrade</a>
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
                    @can('tax-consultant-fee-configuration-view')
                        <li class="{{ request()->is('taxagents/fee*') ? 'active' : '' }}">
                            <a href="{{ route('taxagents.fee') }}">Fee Configuration</a>
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
                        <li class="{{ request()->is('e-filling/hotel') ? 'active' : '' }}">
                            <a href="{{ route('returns.hotel.index') }}">Hotel Levy</a>
                        </li>
                    @endcan
                    @can('return-tour-operation-view')
                        <li class="{{ request()->is('e-filling/tour') ? 'active' : '' }}">
                            <a href="{{ route('returns.tour.index') }}">Tour Operation Levy</a>
                        </li>
                    @endcan
                    @can('return-restaurant-levy-view')
                        <li class="{{ request()->is('e-filling/restaurant') ? 'active' : '' }}">
                            <a href="{{ route('returns.restaurant.index') }}">Restaurant Levy</a>
                        </li>
                    @endcan
                    @can('return-vat-return-view')
                        <li class="{{ request()->is('e-filling/vat*') ? 'active' : '' }}">
                            <a href="{{ route('returns.vat-return.index') }}">Vat Returns</a>
                        </li>
                    @endcan
                    @can('return-port-return-view')
                        <li class="{{ request()->is('e-filling/port*') ? 'active' : '' }}">
                            <a href="{{ route('returns.port.index') }}">Port Returns</a>
                        </li>
                    @endcan
                    @can('return-mno-excise-duty-return-view')
                        <li class="{{ request()->is('e-filling/excise-duty/mno*') ? 'active' : '' }}">
                            <a href="{{ route('returns.excise-duty.mno') }}">MNO Excise Duty Returns</a>
                        </li>
                    @endcan
                    @can('return-electronic-money-transaction-return-view')
                        <li class="{{ request()->is('e-filling/em-transaction*') ? 'active' : '' }}">
                            <a href="{{ route('returns.em-transaction.index') }}">Electronic Money Transaction Returns</a>
                        </li>
                    @endcan
                    @can('return-stamp-duty-return-view')
                        <li class="{{ request()->is('e-filling/stamp-duty*') ? 'active' : '' }}">
                            <a href="{{ route('returns.stamp-duty.index') }}">Stamp Duty Returns</a>
                        </li>
                    @endcan
                    @can('return-bfo-excise-duty-return-view')
                        <li class="{{ request()->is('e-filling/bfo-excise-duty*') ? 'active' : '' }}">
                            <a href="{{ route('returns.bfo-excise-duty.index') }}">BFO Excise Duty Return</a>
                        </li>
                    @endcan
                    @can('return-lump-sum-payment-return-view')
                        <li class="{{ request()->is('e-filling/lump-sum*') ? 'active' : '' }}">
                            <a href="{{ route('returns.lump-sum.index') }}">Lump Sum Payments</a>
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
                    @can('withholding-agents-list')
                        <li class="{{ request()->is('withholdingAgents/list*') ? 'active' : '' }}">
                            <a href="{{ route('withholdingAgents.list') }}">Agents List</a>
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
                    @can('quantity-of-certificate-view')
                        <li class="{{ request()->is('petroleum/certificateOfQuantity*') ? 'active' : '' }}">
                            <a href="{{ route('petroleum.certificateOfQuantity.index') }}">Certificate of Quantity</a>
                        </li>
                    @endcan
                    @can('return-petroleum-return-view')
                        <li class="{{ request()->is('petroleum/filling*') ? 'active' : '' }}">
                            <a href="{{ route('petroleum.filling.index') }}">Petroleum Return</a>
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
        @can('tax-claim')
            @can('tax-claim-view')
                <li class="{{ request()->is('tax-claims*') ? 'active' : '' }}">
                    <a href="{{ route('claims.index') }}">Tax Claims</a>
                </li>
            @endcan
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

        @can('payment-installment')
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

        @can('tax-auditing')
            <li class="{{ request()->is('tax_auditing*') ? 'active' : '' }}">
                <a href="#tax_auditing" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    Tax Auditing
                </a>
                <ul class="collapse list-unstyled {{ request()->is('tax_auditing*') ? 'show' : '' }}" id="tax_auditing">
                    @can('tax-Auditing-approved-view')
                        <li class="{{ request()->is('tax_auditing/approvals*') ? 'active' : '' }}">
                            <a href="{{ route('tax_auditing.approvals.index') }}">Approvals</a>
                        </li>
                    @endcan
                    @can('tax-Auditing-approved-view')
                        <li class="{{ request()->is('tax_auditing/assessments*') ? 'active' : '' }}">
                            <a href="{{ route('tax_auditing.assessments.index') }}">Assessments</a>
                        </li>
                    @endcan
                    @can('tax-Auditing-approved-view')
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

        @can('dispute-management')
            <li class="{{ request()->is('assesments*') ? 'active' : '' }}">
                <a href="#assesmentsSubmenu" data-toggle="collapse"
                    aria-expanded="{{ request()->is('assesments*') ? 'true' : 'false' }}"
                    class="dropdown-toggle">Disputes Management</a>
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
        @can('tax-clearance-management')
            <li class="{{ request()->is('tax-clearance*') ? 'active' : '' }}">
                <a href="#taxClearance" data-toggle="collapse"
                    aria-expanded="{{ request()->is('/tax-clearance*') ? 'true' : 'false' }}" class="dropdown-toggle">
                    Tax Clearance Management
                </a>
                <ul class="collapse list-unstyled {{ request()->is('tax-clearance*') ? 'show' : '' }}"
                    id="taxClearance">
                    @can('tax-clearance-view')
                        <li class="{{ request()->is('tax-clearance/tax-clearance*') ? 'active' : '' }}">
                            <a href="{{ route('tax-clearance.index') }}">Requests</a>
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
                        <li class="{{ request()->is('debts/debt*') ? 'active' : '' }}">
                            <a href="{{ route('debts.debt.index') }}">Debts</a>
                        </li>
                    @endcan
                    @can('debt-management-debts-overdue-view')
                        <li class="{{ request()->is('debts/overdue*') ? 'active' : '' }}">
                            <a href="{{ route('debts.debt.overdue') }}">Overdue Debts</a>
                        </li>
                    @endcan
                    @can('debt-management-waiver-debt-view')
                        <li class="{{ request()->is('debts/waiver*') ? 'active' : '' }}">
                            <a href="{{ route('debts.waivers.index') }}">Waiver Requests</a>
                        </li>
                    @endcan
                    @can('debt-management-assessment-debt-view')
                        <li class="{{ request()->is('debts/assessments*') ? 'active' : '' }}">
                            <a href="{{ route('debts.assessments.index') }}">Assessment Debts</a>
                        </li>
                    @endcan
                    @can('debt-management-hotel-levy-view')
                        <li class="{{ request()->is('debts/returns/hotel*') ? 'active' : '' }}">
                            <a href="{{ route('debts.hotel.index', encrypt(App\Models\TaxType::HOTEL)) }}">Hotel
                                Levy</a>
                        </li>
                    @endcan
                    @can('debt-management-restaurant-levy-view')
                        <li class="{{ request()->is('debts/returns/restaurant*') ? 'active' : '' }}">
                            <a href="{{ route('debts.restaurant.index', encrypt(App\Models\TaxType::RESTAURANT)) }}">Restaurant
                                Levy</a>
                        </li>
                    @endcan
                    @can('debt-management-tour-operation-levy-view')
                        <li class="{{ request()->is('debts/returns/tour*') ? 'active' : '' }}">
                            <a href="{{ route('debts.tour.index', encrypt(App\Models\TaxType::TOUR_OPERATOR)) }}">Tour
                                Operation
                                Levy</a>
                        </li>
                    @endcan
                    @can('debt-management-petroleum-levy-view')
                        <li class="{{ request()->is('debts/returns/petroleum*') ? 'active' : '' }}">
                            <a href="{{ route('debts.petroleum.index', encrypt(App\Models\TaxType::PETROLEUM)) }}">Petroleum
                                Returns</a>
                        </li>
                    @endcan
                    @can('debt-management-vat-return-view')
                        <li class="{{ request()->is('debts/returns/vat*') ? 'active' : '' }}">
                            <a href="{{ route('debts.vat.index', encrypt(App\Models\TaxType::VAT)) }}">VAT
                                Returns</a>
                        </li>
                    @endcan
                    @can('debt-management-stamp-duty-return-view')
                        <li class="{{ request()->is('debts/returns/stamp-duty*') ? 'active' : '' }}">
                            <a href="{{ route('debts.stamp-duty.index', encrypt(App\Models\TaxType::STAMP_DUTY)) }}">Stamp
                                Duty
                                Returns</a>
                        </li>
                    @endcan
                    @can('debt-management-lump-sum-return-view')
                        <li class="{{ request()->is('debts/returns/lump-sum*') ? 'active' : '' }}">
                            <a href="{{ route('debts.lump-sum.index', encrypt(App\Models\TaxType::LUMPSUM_PAYMENT)) }}">Lump
                                Sum Returns</a>
                        </li>
                    @endcan
                    @can('debt-management-electronic-money-transaction-view')
                        <li class="{{ request()->is('debts/returns/emt*') ? 'active' : '' }}">
                            <a
                                href="{{ route('debts.emt.index', encrypt(App\Models\TaxType::ELECTRONIC_MONEY_TRANSACTION)) }}">Electronic
                                Money Transaction</a>
                        </li>
                    @endcan
                    @can('debt-management-sea-services-transport-view')
                        <li class="{{ request()->is('debts/returns/sea*') ? 'active' : '' }}">
                            <a
                                href="{{ route('debts.sea.index', encrypt(App\Models\TaxType::SEA_SERVICE_TRANSPORT_CHARGE)) }}">Sea
                                Service Transport
                            </a>
                        </li>
                    @endcan
                    @can('debt-management-air-port-safety-fee-view')
                        <li class="{{ request()->is('debts/returns/airport*') ? 'active' : '' }}">
                            <a
                                href="{{ route('debts.airport.index', encrypt(App\Models\TaxType::AIRPORT_SERVICE_SAFETY_FEE)) }}">Airport
                                Service Safety Fee
                            </a>
                        </li>
                    @endcan
                    @can('debt-management-bfo-returns-view')
                        <li class="{{ request()->is('debts/returns/bfo*') ? 'active' : '' }}">
                            <a href="{{ route('debts.bfo.index', encrypt(App\Models\TaxType::EXCISE_DUTY_BFO)) }}">BFO
                                Returns</a>
                        </li>
                    @endcan
                    @can('debt-management-mno-returns-view')
                        <li class="{{ request()->is('debts/returns/mno*') ? 'active' : '' }}">
                            <a href="{{ route('debts.mno.index', encrypt(App\Models\TaxType::EXCISE_DUTY_MNO)) }}">MNO
                                Returns</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        <li class="{{ request()->is('mvr*') ? 'active' : '' }}">
            <a href="#mvrSubmenu" data-toggle="collapse"
                aria-expanded="{{ request()->is('mvr*') ? 'true' : 'false' }}" class="dropdown-toggle">Motor Vehicle
                Registration</a>
            <ul class="collapse list-unstyled {{ request()->is('mvr*') ? 'show' : '' }}" id="mvrSubmenu">
                <li class="{{ request()->is('mvr/register') ? 'active' : '' }}">
                    <a href="{{ route('mvr.register') }}">Motor Vehicle Registration</a>
                </li>
                @canany(['receive_plate_number', 'print_plate_number'])
                    <li class="{{ request()->is('mvr/plate-numbers') ? 'active' : '' }}">
                        <a href="{{ route('mvr.plate-numbers') }}">Plate Number Printing</a>
                    </li>
                @endcanany
                <li class="{{ request()->is('mvr/reg-change-requests') ? 'active' : '' }}">
                    <a href="{{ route('mvr.reg-change-requests') }}">Status Change Requests</a>
                </li>
                <li class="{{ request()->is('mvr/transfer-ownership*') ? 'active' : '' }}">
                    <a href="{{ route('mvr.transfer-ownership') }}">Transfer Ownership</a>
                </li>
                <li class="{{ request()->is('mvr/de-register-requests*') ? 'active' : '' }}">
                    <a href="{{ route('mvr.de-register-requests') }}">De-registration</a>
                </li>
                <li class="{{ request()->is('mvr/written-off') ? 'active' : '' }}">
                    <a href="{{ route('mvr.written-off') }}">Written-off Vehicles</a>
                </li>
                <li class="{{ request()->is('mvr/registered') ? 'active' : '' }}">
                    <a href="{{ route('mvr.registered') }}">Registered Motor Vehicles</a>
                </li>
                <li class="{{ request()->is('mvr/agent') ? 'active' : '' }}">
                    <a href="{{ route('mvr.agent') }}">Transport Agents</a>
                </li>
            </ul>
        </li>

        <li  class="{{ (request()->is('drivers-license*') || request()->is('rio*')) ? 'active':'' }}">
            <a href="#dlSubmenu" data-toggle="collapse" aria-expanded="{{ (request()->is('drivers-license*') || request()->is('rio*'))? 'true' : 'false' }}" class="dropdown-toggle">Driver's Licenses</a>
            <ul class="collapse list-unstyled {{ (request()->is('drivers-license*') || request()->is('drivers-license*')) ? 'show' : '' }}" id="dlSubmenu">
                <li class="{{ (request()->is('drivers-license/applications') || request()->is('drivers-license*')) ? 'active': '' }}">
                    <a href="{{ route('drivers-license.applications') }}">Driver's License Applications</a>
                </li>
                <li class="{{ request()->is('rio*') ? 'active': '' }}">
                    <a href="{{ route('rio.register') }}">Road Inspection Offences</a>
                </li>
            </ul>
        </li>

        <li  class="{{ request()->is('cases*') ? 'active':'' }}">
            <a href="#lcmSubmenu" data-toggle="collapse" aria-expanded="{{ request()->is('cases*') ? 'true' : 'false' }}" class="dropdown-toggle">Legal Cases Management</a>
            <ul class="collapse list-unstyled {{ request()->is('cases*') ? 'show' : '' }}" id="lcmSubmenu">
                <li class="{{ request()->is('cases') ? 'active': '' }}">
                    <a href="{{ route('cases.index') }}">Cases</a>
                </li>
                <li class="{{ request()->is('cases/appeals') ? 'active': '' }}">
                    <a href="{{ route('cases.appeals') }}">Appeals</a>
                </li>
                <li class="{{ request()->is('reports/registration*') ? 'active' : '' }}">
                    <a href="{{ route('reports.registration.init') }}">Registration Reports</a>
                </li>
            </ul>
        </li>

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
                    <li class="{{ request()->is('land-lease/generate-report*') ? 'active' : '' }}">
                        <a href="{{ route('land-lease.generate.report') }}">Generate Report</a>
                    </li>
                    <li class="{{ request()->is('land-lease/agents*') ? 'active' : '' }}">
                        <a href="{{ route('land-lease.agents') }}">Land Lease Agents</a>
                    </li>
                </ul>
            </li>
        @endcan
        @can('managerial-report-management')
            <li class="{{ request()->is('reports*') ? 'active' : '' }}">
                <a href="#reportSubmenu" data-toggle="collapse"
                    aria-expanded="{{ request()->is('reports*') ? 'true' : 'false' }}" class="dropdown-toggle">
                    Managerial reports
                </a>
                <ul class="collapse list-unstyled {{ request()->is('reports*') ? 'show' : '' }}" id="reportSubmenu">
                    @can('managerial-report-view')
                        <li class="{{ request()->is('reports/returns*') ? 'active' : '' }}">
                            <a href="{{ route('reports.returns') }}">Return Reports</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        <li class="{{ request()->is('queries*') ? 'active' : '' }}">
            <a href="#queriesSubmenu" data-toggle="collapse"
                aria-expanded="{{ request()->is('queries*') ? 'true' : 'false' }}" class="dropdown-toggle">
                Return Queries
            </a>
            <ul class="collapse list-unstyled {{ request()->is('queries*') ? 'show' : '' }}" id="queriesSubmenu">

                <li class="{{ request()->is('queries/non-filers*') ? 'active' : '' }}">
                    <a href="{{ route('queries.nil-returns') }}">Nil Returns</a>
                </li>

                <li class="{{ request()->is('queries/non-filers*') ? 'active' : '' }}">
                    <a href="{{ route('queries.non-filers') }}">Non Filers</a>
                </li>

                <li class="{{ request()->is('queries/sales-purchases*') ? 'active' : '' }}">
                    <a href="{{ route('queries.sales-purchases') }}">Sales Vs Purchases</a>
                </li>

                <li class="{{ request()->is('queries/all-credit-returns*') ? 'active' : '' }}">
                    <a href="{{ route('queries.all-credit-returns') }}">All Credit Returns</a>
                </li>
            </ul>
        </li>

        @can('manage-payment-management')
            <li class="{{ request()->is('payments*') ? 'active' : '' }}">
                <a href="#payments" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    Manage Payments
                </a>
                <ul class="collapse list-unstyled {{ request()->is('payments*') ? 'show' : '' }}" id="payments">
                    @can('manage-payments-view')
                        <li class="{{ request()->is('payments/completed*') ? 'active' : '' }}">
                            <a href="{{ route('payments.complete') }}">Complete Payments</a>
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
                    @can('setting-banks-view')
                        <li class="{{ request()->is('settings/banks*') ? 'active' : '' }}">
                            <a href="{{ route('settings.banks.index') }}">Banks</a>
                        </li>
                    @endcan
                    @can('setting-exchange-rate-view')
                        <li class="{{ request()->is('settings/exchange-rate*') ? 'active' : '' }}">
                            <a href="{{ route('settings.exchange-rate.index') }}">Exchange Rate</a>
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
                        <li class="{{ request()->is('settings/country*') ? 'active' : '' }}">
                            <a href="{{ route('settings.business-files.index') }}">Business Files</a>
                        </li>
                    @endcan
                    @can('setting-region-view')
                        <li class="{{ request()->is('settings/tax-regions*') ? 'active' : '' }}">
                            <a href="{{ route('settings.tax-regions.index') }}">Tax Regions</a>
                        </li>
                    @endcan
                    @can('setting-mvr-make-view')
                        <li class="{{ request()->is('settings/mvr-generic/MvrMake') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'MvrMake') }}">Motor Vehicle Make</a>
                        </li>
                    @endcan
                    @can('setting-mvr-model-view')
                        <li class="{{ request()->is('settings/mvr-generic/MvrModel') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'MvrModel') }}">Motor Vehicle Model</a>
                        </li>
                    @endcan
                    @can('setting-mvr-transmission-type-view')
                        <li class="{{ request()->is('settings/mvr-generic/MvrTransmissionType') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'MvrTransmissionType') }}">Motor Vehicle
                                Transmission</a>
                        </li>
                    @endcan
                    @can('setting-mvr-fuel-type-view')
                        <li class="{{ request()->is('settings/mvr-generic/MvrFuelType') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'MvrFuelType') }}">Motor vehicle Fuel Type</a>
                        </li>
                    @endcan
                    @can('setting-mvr-class-view')
                        <li class="{{ request()->is('settings/mvr-generic/MvrClass') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'MvrClass') }}">Motor Vehicle Class</a>
                        </li>
                    @endcan
                    @can('setting-mvr-color-view')
                        <li class="{{ request()->is('settings/mvr-generic/MvrColor') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'MvrColor') }}">Motor Vehicle Color</a>
                        </li>
                    @endcan
                    @can('setting-mvr-body-type-view')
                        <li class="{{ request()->is('settings/mvr-generic/MvrBodyType') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'MvrBodyType') }}">Motor Vehicle Body Type</a>
                        </li>
                    @endcan
                    @can('setting-mvr-plate-size-view')
                        <li class="{{ request()->is('settings/mvr-generic/MvrPlateSize') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'MvrPlateSize') }}">Motor Vehicle Plate Size</a>
                        </li>
                    @endcan
                    @can('setting-mvr-fee-view')
                        <li class="{{ request()->is('settings/mvr-generic/MvrFee') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'MvrFee') }}">Motor Vehicle Fees</a>
                        </li>
                    @endcan
                    @can('setting-mvr-deregistration-reason-view')
                        <li class="{{ request()->is('settings/mvr-generic/MvrDeRegistrationReason') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'MvrDeRegistrationReason') }}">De Registration
                                Reasons</a>
                        </li>
                    @endcan
                    @can('setting-mvr-ownership-transfer-reason-view')
                        <li class="{{ request()->is('settings/mvr-generic/MvrOwnershipTransferReason') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index', 'MvrOwnershipTransferReason') }}">Transfer
                                Reasons</a>
                        </li>
                    @endcan
                    @can('setting-transfer-category-view')
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
                        <li class="{{ request()->is('settings/mvr-generic/DlLicenseClass') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index','DlLicenseClass') }}">Driver's License Classes</a>
                        </li>
                        <li class="{{ request()->is('settings/mvr-generic/DlLicenseDuration') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index','DlLicenseDuration') }}">Driver's License Duration</a>
                        </li>
                        <li class="{{ request()->is('settings/mvr-generic/DlBloodGroup') ? 'active' : '' }}">
                            <a href="{{ route('settings.mvr-generic.index','DlBloodGroup') }}">Blood Groups</a>
                        </li>
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
                    @can('system-all-pdfs-view')
                        <li class="{{ request()->is('system/workflow*') ? 'active' : '' }}">
                            <a href="{{ route('pdf.all') }}">All PDF's</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

    </ul>

    <div class="profile d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <i class="far fa-2x fa-user-circle"></i>
            </div>
            <div class="pl-2">
                <div>{{ auth()->user()->fullname() }}</div>
                <div>{{ auth()->user()->role->name ?? '' }}</div>
            </div>
        </div>

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