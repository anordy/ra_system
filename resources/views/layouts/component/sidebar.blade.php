<nav id="sidebar">
    <div class="sidebar-header text-center">
        <h3><i class="fas fa-money-check"></i> ZTMS</h3>
    </div>

    <ul class="list-unstyled components">
        <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
            <a href="{{ route('home') }}">Dashboard</a>
        </li>
        <li class="{{ request()->is('users*') ? 'active' : '' }}">
            <a href="{{ route('users.index') }}">Users</a>
        </li>
        <li class="{{ request()->is('settings*') ? 'active' : '' }}">
            <a href="#settings" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Settings</a>
            <ul class="collapse list-unstyled" id="settings">
                <li class="{{ request()->is('settings/roles*') ? 'active' : '' }}">
                    <a href="{{ route('settings.roles.index') }}">Roles</a>
                </li>
                <li class="{{ request()->is('settings/country*') ? 'active' : '' }}">
                    <a href="{{ route('settings.country.index') }}">Countries</a>
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
