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
        <li>
            <a href="#">About</a>
        </li>
        <li>
            <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Pages</a>
            <ul class="collapse list-unstyled" id="pageSubmenu">
                <li>
                    <a href="#">Page 1</a>
                </li>
                <li>
                    <a href="#">Page 2</a>
                </li>
                <li>
                    <a href="#">Page 3</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">Portfolio</a>
        </li>
        <li>
            <a href="#">Contact</a>
        </li>
    </ul>

    <div class="profile d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <i class="far fa-2x fa-user-circle"></i>
            </div>
            <div class="pl-2">
                <div>First Name Last Name</div>
                <div>Role</div>
            </div>
        </div>
        
        <div class="pr-1">
            <i class="fas fa-sign-out-alt"></i>
        </div>
    </div>
</nav>
