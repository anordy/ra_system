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
        <li class="{{ request()->is('taxpayers*') ? 'active' : '' }}">
            <a href="#taxpayersMenu" data-toggle="collapse"
                aria-expanded="{{ request()->is('taxpayers*') ? 'true' : 'false' }}"
                class="dropdown-toggle">Taxpayers Management</a>
            <ul class="collapse list-unstyled {{ request()->is('taxpayers*') ? 'show' : '' }}" id="taxpayersMenu">
                <li class="{{ request()->is('taxpayers/taxpayer*') ? 'active' : '' }}">
                    <a href="{{ route('taxpayers.taxpayer.index') }}">Taxpayers</a>
                </li>
                <li class="{{ request()->is('taxpayers/registrations*') ? 'active' : '' }}">
                    <a href="{{ route('taxpayers.registrations.index') }}">KYC</a>
                </li>
            </ul>
        </li>


        <li class="{{ request()->is('business*') ? 'active' : '' }}">
            <a href="#businessMenu" data-toggle="collapse"
                aria-expanded="{{ request()->is('business*') ? 'true' : 'false' }}"
                class="dropdown-toggle">Business Management</a>
            <ul class="collapse list-unstyled {{ request()->is('business*') ? 'show' : '' }}" id="businessMenu">
                @can('business_registrations_view')
                    <li class="{{ request()->is('business/registrations*') ? 'active' : '' }}">
                        <a href="{{ route('business.registrations.index') }}">Registrations</a>
                    </li>
                @endcan
                <li class="{{ request()->is('business/branches*') ? 'active' : '' }}">
                    <a href="{{ route('business.branches.index') }}">Branches</a>
                </li>
                <li class="{{ request()->is('business/deregistrations*') ? 'active' : '' }}">
                    <a href="{{ route('business.deregistrations') }}">De-registrations</a>
                </li>
                <li class="{{ request()->is('business/closure*') ? 'active' : '' }}">
                    <a href="{{ route('business.closure') }}">Temporary Closures</a>
                </li>
                <li class="{{ request()->is('business/updates*') ? 'active' : '' }}">
                    <a href="{{ route('business.updatesRequests') }}">Business Updates Requests</a>
                    @can('change_tax_type_view')
                    <li class="{{ request()->is('business/taxTypeRequests*') ? 'active' : '' }}">
                        <a href="{{ route('business.taxTypeRequests') }}">Tax Type Changes Requests</a>
                    </li>
                @endcan
            </ul>
        </li>
        <li class="{{ request()->is('taxagents*') ? 'active' : '' }}">
            <a href="#taxagentSubmenu" data-toggle="collapse"
                aria-expanded="{{ request()->is('taxagents*') ? 'true' : 'false' }}" class="dropdown-toggle">Tax
                Consultants</a>
            <ul class="collapse list-unstyled {{ request()->is('taxagents*') ? 'show' : '' }}" id="taxagentSubmenu">
                <li class="{{ request()->is('taxagents/requests') ? 'active' : '' }}">
                    <a href="{{ route('taxagents.requests') }}">Registration Requests</a>
                </li>
                <li class="{{ request()->is('taxagents/active*') ? 'active' : '' }}">
                    <a href="{{ route('taxagents.active') }}">Active Tax Consultants</a>
                </li>
                <li class="{{ request()->is('taxagents/renew*') ? 'active' : '' }}">
                    <a href="{{ route('taxagents.renew') }}">Renewal Requests</a>
                </li>
                <li class="{{ request()->is('taxagents/fee*') ? 'active' : '' }}">
                    <a href="{{ route('taxagents.fee') }}">Fee Configuration</a>
                </li>
            </ul>
        </li>

        

        @can('withholding_agents_view')
            <li class="{{ request()->is('withholdingAgents*') ? 'active' : '' }}">
                <a href="#withholdingAgentsMenu" data-toggle="collapse"
                    aria-expanded="{{ request()->is('withholdingAgents*') ? 'true' : 'false' }}"
                    class="dropdown-toggle">Withholding Agents</a>
                <ul class="collapse list-unstyled {{ request()->is('withholdingAgents*') ? 'show' : '' }}"
                    id="withholdingAgentsMenu">
                    @can('withholding_agents_add')
                        <li class="{{ request()->is('withholdingAgents/register*') ? 'active' : '' }}">
                            <a href="{{ route('withholdingAgents.register') }}">Registration</a>
                        </li>
                    @endcan
                    <li class="{{ request()->is('withholdingAgents/list*') ? 'active' : '' }}">
                        <a href="{{ route('withholdingAgents.list') }}">Withholding Agents</a>
                    </li>
                </ul>
            </li>
        @endcan

        <li class="{{ request()->is('settings*') ? 'active' : '' }}">
            <a href="#settings" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Settings</a>
            <ul class="collapse list-unstyled {{ request()->is('settings*') ? 'show' : '' }}" id="settings">
                @can('roles_add')
                    <li class="{{ request()->is('settings/users*') ? 'active' : '' }}">
                        <a href="{{ route('settings.users.index') }}">Users</a>
                    </li>
                @endcan
                @can('roles_add')
                    <li class="{{ request()->is('settings/roles*') ? 'active' : '' }}">
                        <a href="{{ route('settings.roles.index') }}">Roles</a>
                    </li>
                @endcan
                <li class="{{ request()->is('settings/country*') ? 'active' : '' }}">
                    <a href="{{ route('settings.country.index') }}">Countries</a>
                </li>
                <li class="{{ request()->is('settings/region*') ? 'active' : '' }}">
                    <a href="{{ route('settings.region.index') }}">Region</a>
                </li>
                <li class="{{ request()->is('settings/district*') ? 'active' : '' }}">
                    <a href="{{ route('settings.district.index') }}">District</a>
                </li>
                <li class="{{ request()->is('settings/ward*') ? 'active' : '' }}">
                    <a href="{{ route('settings.ward.index') }}">Ward</a>
                </li>
                <li class="{{ request()->is('settings/banks*') ? 'active' : '' }}">
                    <a href="{{ route('settings.banks.index') }}">Banks</a>
                </li>
                <li class="{{ request()->is('settings/education-level*') ? 'active' : '' }}">
                    <a href="{{ route('settings.education-level.index') }}">Education Level</a>
                </li>
                <li class="{{ request()->is('settings/business-categories*') ? 'active' : '' }}">
                    <a href="{{ route('settings.business-categories.index') }}">Business categories</a>
                </li>
                <li class="{{ request()->is('settings/taxtypes*') ? 'active' : '' }}">
                    <a href="{{ route('settings.taxtypes.index') }}">Tax Types</a>
                </li>
                <li class="{{ request()->is('settings/isic1*') ? 'active' : '' }}">
                    <a href="{{ route('settings.isic1.index') }}">ISIC Level 1</a>
                </li>
                <li class="{{ request()->is('settings/isic2*') ? 'active' : '' }}">
                    <a href="{{ route('settings.isic2.index') }}">ISIC Level 2</a>
                </li>
                <li class="{{ request()->is('settings/isic3*') ? 'active' : '' }}">
                    <a href="{{ route('settings.isic3.index') }}">ISIC Level 3</a>
                </li>
                <li class="{{ request()->is('settings/isic4*') ? 'active' : '' }}">
                    <a href="{{ route('settings.isic4.index') }}">ISIC Level 4</a>
                </li>
                <li class="{{ request()->is('settings/country*') ? 'active' : '' }}">
                    <a href="{{ route('settings.business-files.index') }}">Business Files</a>
                </li>
                <li class="{{ request()->is('settings/returns*') ? 'active' : '' }}">
                    <a href="{{ route('settings.returns.index') }}">Returns</a>
                </li>
            </ul>
        </li>
        <li class="{{ request()->is('petroleum*') ? 'active' : '' }}">
            <a href="#petroleum" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Petroleum Management</a>
            <ul class="collapse list-unstyled {{ request()->is('petroleum*') ? 'show' : '' }}" id="petroleum">
                <li class="{{ request()->is('petroleum/certificateOfQuantity*') ? 'active' : '' }}">
                    <a href="{{ route('petroleum.certificateOfQuantity.index') }}">Certificate of Quantity</a>
                </li>
                <li class="{{ request()->is('petroleum/filling*') ? 'active' : '' }}">
                    <a href="{{ route('petroleum.filling.index') }}">Petroleum Return</a>
                </li>
            </ul>
        </li>

        <li class="{{ request()->is('verification*') ? 'active' : '' }}">
            <a href="{{ route('verifications.index') }}">Verifications</a>
        </li>

        <li class="{{ request()->is('auditing*') ? 'active' : '' }}">
            <a href="{{ route('auditings.index') }}">Auditings</a>
        </li>

        <li class="{{ request()->is('investigation*') ? 'active' : '' }}">
            <a href="{{ route('investigations.index') }}">Investigations</a>
        </li>

        <li class="{{ request()->is('reliefs*') ? 'active' : '' }}">
            <a href="{{ route('reliefs.index') }}">Reliefs</a>
        </li>


        <li class="{{ request()->is('system*') ? 'active' : '' }}">
            <a href="#system" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">System</a>
            <ul class="collapse list-unstyled {{ request()->is('system*') ? 'show' : '' }}" id="system">
                <li class="{{ request()->is('system/audits*') ? 'active' : '' }}">
                    <a href="{{ route('system.audits.index') }}">Audit Trail</a>
                </li>
                <li class="{{ request()->is('system/workflow*') ? 'active' : '' }}">
                    <a href="{{ route('system.workflow.index') }}">Workflow Configure</a>
                </li>
            </ul>
        </li>
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
