<nav class="top-navbar navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid p-0 flex-nowrap">
        <div class="justify-content-start">
            <button type="button" id="sidebarCollapse" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-list"></i>
            </button>
            <span class="navbar-brand mb-0 h1 px-3">@yield('title')</span>
        </div>

        <div class="justify-content-end">
            @livewire('header-notification')
        </div>
    </div>
</nav>