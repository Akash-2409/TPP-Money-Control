<nav class="navbar p-0 fixed-top d-flex flex-row">
    {{-- Mobile Logo --}}
    <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
        <a class="navbar-brand brand-logo-mini text-white" href="{{ route('dashboard') }}">
            MN
        </a>
    </div>

    <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">

        {{-- Sidebar Toggle --}}
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>

        {{-- Page Title --}}
        <ul class="navbar-nav w-100">
            <li class="nav-item w-100">
                <h4 class="nav-link mt-2 mt-md-0 text-white">
                    @yield('page-title', 'Dashboard')
                </h4>
            </li>
        </ul>

        {{-- Right Menu --}}
        <ul class="navbar-nav navbar-nav-right">

            {{-- Notifications (Optional) --}}
            <li class="nav-item dropdown border-left">
                <a class="nav-link count-indicator dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="mdi mdi-bell"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list">
                    <h6 class="p-3 mb-0">Notifications</h6>
                    <div class="dropdown-divider"></div>
                    <p class="p-3 mb-0 text-center text-muted">No notifications</p>
                </div>
            </li>

            {{-- Profile --}}
            <li class="nav-item dropdown">
                <a class="nav-link" data-bs-toggle="dropdown">
                    <div class="navbar-profile">
                        <img class="img-xs rounded-circle"
                             src="{{ asset('assets/images/faces/face15.jpg') }}"
                             alt="profile">
                        <p class="mb-0 d-none d-sm-block navbar-profile-name">
                            {{ auth()->user()->name }}
                        </p>
                        <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                    </div>
                </a>

                <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list">
                    <h6 class="p-3 mb-0">Profile</h6>
                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item preview-item" href="#">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-cog text-success"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject mb-1">Settings</p>
                        </div>
                    </a>

                    <div class="dropdown-divider"></div>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-dark rounded-circle">
                                    <i class="mdi mdi-logout text-danger"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <p class="preview-subject mb-1">Logout</p>
                            </div>
                        </button>
                    </form>
                </div>
            </li>

        </ul>

        {{-- Mobile Sidebar Toggle --}}
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center"
                type="button" data-toggle="offcanvas">
            <span class="mdi mdi-format-line-spacing"></span>
        </button>
    </div>
</nav>
