<header class="navbar p-0 d-flex align-items-center justify-content-between px-4">
    
    {{-- Left Side: Hamburger & Title --}}
    <div class="d-flex align-items-center">
        <!-- Sidebar Toggle (Desktop & Mobile) -->
        <button class="navbar-toggler border-0 shadow-none me-3" type="button" data-toggle="minimize" onclick="document.querySelector('.sidebar').classList.toggle('sidebar-open')">
            <i class="mdi mdi-menu fs-3 text-secondary"></i>
        </button>

        <!-- Page Title -->
        <h4 class="mb-0 text-dark fw-bold d-none d-md-block" style="font-family: 'Outfit', sans-serif;">
            @yield('page-title', 'Dashboard')
        </h4>
    </div>

    {{-- Right Side: User Profile --}}
    <ul class="navbar-nav flex-row align-items-center justify-content-end mb-0">

        <!-- Optional Notifications -->
        <li class="nav-item dropdown me-3">
            <a class="nav-link dropdown-toggle position-relative text-secondary d-flex align-items-center justify-content-center" href="javascript:void(0)" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 40px; height: 40px;">
                <i class="mdi mdi-bell fs-4"></i>
                <!-- Red dot indicator -->
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-white rounded-circle">
                    <span class="visually-hidden">New alerts</span>
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end position-absolute shadow-sm border-0 mt-2" aria-labelledby="notificationDropdown" style="border-radius: 12px; width: 250px;">
                <li><h6 class="dropdown-header fw-bold">Notifications</h6></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-muted text-center py-3" href="#">No new notifications</a></li>
            </ul>
        </li>

        <!-- User Profile Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center bg-light px-3 py-2" id="userProfileDropdown" role="button" style="border-radius: 50px;" href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
                <img class="rounded-circle me-2" style="width: 35px; height: 35px; object-fit: cover;" src="{{ asset('assets/images/faces/face15.jpg') }}" alt="profile">
                <div class="d-none d-sm-flex flex-column align-items-start me-2">
                    <span class="text-dark fw-bold" style="font-size: 0.85rem;">{{ auth()->user()->name ?? 'Admin User' }}</span>
                    <span class="text-muted text-capitalize" style="font-size: 0.75rem;">{{ auth()->user()->roles->pluck('name')->join(', ') ?: 'User' }}</span>
                </div>
            </a>
            
            <!-- Dropdown Menu -->
            <ul class="dropdown-menu dropdown-menu-end position-absolute shadow-sm border-0 mt-2 p-2" aria-labelledby="userProfileDropdown" style="border-radius: 12px; min-width: 200px;">
                <li>
                    <a class="dropdown-item d-flex align-items-center py-2 rounded" href="#">
                        <i class="mdi mdi-cog fs-5 text-secondary me-3"></i> Settings
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center py-2 rounded text-danger bg-transparent" style="border:none;">
                            <i class="mdi mdi-logout fs-5 me-3"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </li>

    </ul>
</header>
