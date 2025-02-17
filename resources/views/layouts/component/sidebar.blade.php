<nav id="sidebar" class="mb-3">
    <div class="sidebar-header text-center pb-0">
        <h3 class="mt-2 d-flex justify-content-center align-items-center">
            <div style="height: 38px; width: 38px; border-radius: 50%" class="mr-3">
                <img style="height: 38px; width: 38px; object-fit: contain;" src="{{ asset("images/logo.png") }}"
                     class="rounded-circle" height="38px">
            </div>
            CRDB
        </h3>
    </div>

    <ul class="list-unstyled components">
        <li class="{{ request()->is("dashboard*") ? "active" : "" }}">
            <a href="{{ route("home") }}">Home</a>
        </li>
        


        <li class="{{ request()->is('account*') ? 'active' : '' }}">
            <a href="#accountMenu" data-toggle="collapse"
               aria-expanded="{{ request()->is('account*') ? 'true' : 'false' }}"
               class="dropdown-toggle">{{ __("Account") }}</a>
            <ul class="collapse list-unstyled {{ request()->is('account*') ? 'show' : '' }}" id="accountMenu">
                <li class="{{ request()->is('account') ? 'active' : '' }}">
                    <a href="{{ route('account') }}">{{ __("Account Details") }}</a>
                </li>
               
                <li class="{{ request()->is('account/security-questions') ? 'active' : '' }}">
                    <a href="{{ route('logout') }}" class="logout-link">
                        {{ __("Log out") }}
                    </a>
                </li>
            </ul>

            <div class="profile d-flex justify-content-between align-items-center p-0">
                <a href="{{ route("account") }}" class="d-flex align-items-center justify-content-between">
                    <div>
                        <i class="far fa-2x fa-user-circle"></i>
                    </div>
                    <div class="pl-2">
                        <div>{{ auth()->user()->fullname() }}</div>
                        <div>{{ auth()->user()->role->name ?? "" }}</div>
                    </div>
                </a>

                <div class="pr-1">
                    <a class="text-white logout-link" href="{{ route("logout") }}" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                    <form id="logout-form" action="{{ route("logout") }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </li>
    </ul>
</nav>
