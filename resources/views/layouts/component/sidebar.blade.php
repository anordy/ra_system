<nav id="sidebar">
    <div class="sidebar-header text-center pb-0">
        <h3 class="mt-2"><i class="bi bi-card-heading"></i> ZITMAS</h3>
    </div>

    <ul class="list-unstyled components">
        <li class="{{ request()->is('/dashboard') ? 'active' : '' }}">
            <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Home</a>
            <ul class="collapse list-unstyled" id="homeSubmenu">
                <li>
                    <a href="#">Home 1</a>
                </li>
                <li>
                    <a href="#">Home 2</a>
                </li>
                <li>
                    <a href="#">Home 3</a>
                </li>
            </ul>
        </li>
        <li class="{{ request()->is('taxpayers*') ? 'active' : '' }}">
            <a href="#taxpayersMenu" data-toggle="collapse" aria-expanded="{{ request()->is('taxpayers*') ? 'true' : 'false' }}" class="dropdown-toggle">Taxpayers</a>
            <ul class="collapse list-unstyled {{ request()->is('taxpayers*') ? 'show' : '' }}" id="taxpayersMenu">
                <li class="{{ request()->is('taxpayers') ? 'active' : '' }}">
                    <a href="{{ route('taxpayers.index') }}">Taxpayers</a>
                </li>
                <li class="{{ request()->is('taxpayers/registrations*') ? 'active' : '' }}">
                    <a href="{{ route('taxpayers.registrations.index') }}">KYC</a>
                </li>
            </ul>
        </li>

        <li class="{{ request()->is('business*') ? 'active' : '' }}">
            <a href="#businessMenu" data-toggle="collapse" aria-expanded="{{ request()->is('business*') ? 'true' : 'false' }}" class="dropdown-toggle">Business</a>
            <ul class="collapse list-unstyled {{ request()->is('business*') ? 'show' : '' }}" id="businessMenu">
                <li class="{{ request()->is('business/registrations*') ? 'active' : '' }}">
                    <a href="{{ route('business.registrations.index') }}">Registrations</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="/audits">Audit Trail</a>
        </li>
        <li>
            <a href="#">About</a>
        </li>
        <li class="{{ request()->is('settings*') ? 'active' : '' }}">
            <a href="#settings" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Settings</a>
            <ul class="collapse list-unstyled {{ request()->is('settings*') ? 'show' : '' }}" id="settings">
                <li class="{{ request()->is('settings/users*') ? 'active' : '' }}">
                    <a href="{{ route('settings.users.index') }}">Users</a>
                </li>
                <li class="{{ request()->is('settings/roles*') ? 'active' : '' }}">
                    <a href="{{ route('settings.roles.index') }}">Roles</a>
                </li>
                <li class="{{ request()->is('settings/country*') ? 'active' : '' }}">
                    <a href="{{ route('settings.country.index') }}">Countries</a>
                </li>
                <li class="{{ request()->is('settings/region*') ? 'active' : '' }}">
                    <a href="{{ route('settings.region.index') }}">Region</a>
                </li>
                <li class="{{ request()->is('settings/district*') ? 'active' : '' }}">
                    <a href="{{ route('settings.district.index') }}">District</a>
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
                <div>Role</div>
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
