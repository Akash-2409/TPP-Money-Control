


<nav class="sidebar sidebar-offcanvas" id="sidebar">
    {{-- Brand --}}
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
        <a class="sidebar-brand brand-logo text-white text-decoration-none" href="{{ route('dashboard') }}">
            Money Notebook
        </a>
        <a class="sidebar-brand brand-logo-mini text-white" href="{{ route('dashboard') }}">
            MN
        </a>
    </div>

    <ul class="nav">

        {{-- Profile --}}
        <li class="nav-item profile">
            <div class="profile-desc">
                <div class="profile-pic">
                    <div class="count-indicator">
                        <img class="img-xs rounded-circle"
                             src="{{ asset('assets/images/faces/face15.jpg') }}" alt="profile">
                        <span class="count bg-success"></span>
                    </div>
                    <div class="profile-name">
                        <h5 class="mb-0 font-weight-normal">{{ auth()->user()->name ?? 'Admin' }}</h5>
                        <span>Administrator</span>
                    </div>
                </div>
            </div>
        </li>

        {{-- Navigation --}}
        <li class="nav-item nav-category">
            <span class="nav-link">Navigation</span>
        </li>

        {{-- Dashboard --}}
        <li class="nav-item menu-items">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               href="{{ route('dashboard') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-speedometer"></i>
                </span>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        {{-- Ledger --}}
        <li class="nav-item nav-category">
            <span class="nav-link">Ledger</span>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}"
               href="{{ route('transactions.index') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-cash-multiple"></i>
                </span>
                <span class="menu-title">Transactions</span>
            </a>
        </li>

        {{-- Production --}}
        <li class="nav-item nav-category">
            <span class="nav-link">Production</span>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
               href="{{ route('products.index') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-package-variant"></i>
                </span>
                <span class="menu-title">Products</span>
            </a>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link {{ request()->routeIs('production.*') ? 'active' : '' }}"
               href="{{ route('production.index') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-factory"></i>
                </span>
                <span class="menu-title">Daily Production</span>
            </a>
        </li>

        {{-- Inventory --}}
        <li class="nav-item nav-category">
            <span class="nav-link">Inventory</span>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}"
               href="{{ route('sales.index') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-cart-outline"></i>
                </span>
                <span class="menu-title">Sales / Dispatch</span>
            </a>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}"
               href="{{ route('purchases.index') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-file-document-outline"></i>
                </span>
                <span class="menu-title">Purchases</span>
            </a>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link {{ request()->routeIs('materials.*') ? 'active' : '' }}"
               href="{{ route('materials.index') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-cube-outline"></i>
                </span>
                <span class="menu-title">Materials</span>
            </a>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}"
               href="{{ route('inventory.index') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-warehouse"></i>
                </span>
                <span class="menu-title">Inventory</span>
            </a>
        </li>

        {{-- Workers --}}
        <li class="nav-item nav-category">
            <span class="nav-link">Workers</span>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link {{ request()->routeIs('workers.index') ? 'active' : '' }}"
               href="{{ route('workers.index') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-account-group"></i>
                </span>
                <span class="menu-title">Workers</span>
            </a>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link {{ request()->routeIs('workers.monthly') ? 'active' : '' }}"
               href="{{ route('workers.monthly') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-file-chart"></i>
                </span>
                <span class="menu-title">Monthly Salary</span>
            </a>
        </li>

        {{-- Reports --}}
        <li class="nav-item nav-category">
            <span class="nav-link">Reports</span>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link {{ request()->routeIs('report.product.monthly') ? 'active' : '' }}"
               href="{{ route('report.product.monthly') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-chart-bar"></i>
                </span>
                <span class="menu-title">Product Monthly</span>
            </a>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link {{ request()->routeIs('report.stock.ledger') ? 'active' : '' }}"
               href="{{ route('report.stock.ledger') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-book-open-variant"></i>
                </span>
                <span class="menu-title">Stock Ledger</span>
            </a>
        </li>

    </ul>
</nav>
