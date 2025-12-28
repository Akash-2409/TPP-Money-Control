<!-- <div class="bg-dark text-white p-3" style="width: 240px; height: 100vh; position: fixed;">
    <h4 class="text-center">Money Notebook</h4>
    <hr>

    <ul class="nav flex-column">

        <li class="nav-item mb-2">
            <a class="nav-link text-white" href="{{ route('dashboard') }}">Dashboard</a>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link text-white" href="{{ route('transactions.index') }}">Transactions</a>
        </li>

    

        <li class="nav-item mb-2">
            <a class="nav-link text-white" href="{{ route('products.index') }}">Products</a>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link text-white" href="{{ route('production.index') }}">Production</a>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link text-white" href="{{ route('workers.index') }}">Workers</a>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link text-white" href="{{ route('worker-advances.index') }}">Worker Uddhar</a>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link text-white" href="{{ route('workers.monthly') }}">Worker Reports</a>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link text-white" href="{{ route('inventory.index') }}">Inventroy</a>
        </li>

    </ul>
</div> -->


<div class="sidebar bg-dark text-white p-3" style="width: 240px; height: 100vh; position: fixed;">

    <h4 class="mb-4">Money Notebook</h4>

    {{-- Dashboard --}}
    <li class="nav-item mb-2">
        <a class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active-menu' : '' }}" 
           href="{{ route('dashboard') }}">
           <i class="fa-solid fa-gauge"></i> Dashboard
        </a>
    </li>

    <hr class="text-secondary">

    {{-- Ledger --}}
    <h6 class="text-secondary">Ledger</h6>

    <li class="nav-item mb-2">
        <a class="nav-link text-white {{ request()->routeIs('transactions.*') ? 'active-menu' : '' }}"
           href="{{ route('transactions.index') }}">
           <i class="fa-solid fa-money-bill"></i> Transactions
        </a>
    </li>

    <hr class="text-secondary">

    {{-- Products & Production --}}
    <h6 class="text-secondary">Production</h6>

    <li class="nav-item mb-2">
        <a class="nav-link text-white {{ request()->routeIs('products.index') ? 'active-menu' : '' }}"
           href="{{ route('products.index') }}">
           <i class="fa-solid fa-box"></i> Products
        </a>
    </li>

    <li class="nav-item mb-2">
        <a class="nav-link text-white {{ request()->routeIs('production.index') ? 'active-menu' : '' }}"
           href="{{ route('production.index') }}">
           <i class="fa-solid fa-industry"></i> Daily Production
        </a>
    </li>

    <hr class="text-secondary">

    {{-- Inventory --}}
    <h6 class="text-secondary">Inventory</h6>

    <li class="nav-item mb-2">
        <a class="nav-link text-white {{ request()->routeIs('sales.*') ? 'active-menu' : '' }}" 
        href="{{ route('sales.index') }}">
        <i class="fa-solid fa-cart-shopping"></i> Sales / Dispatch
        </a>
    </li>

    <li class="nav-item mb-2">
        <a class="nav-link text-white {{ request()->routeIs('purchases.*') ? 'active-menu' : '' }}"
            href="{{ route('purchases.index') }}">
            <i class="fa-solid fa-file-invoice"></i> Purchases
        </a>
    </li>
    <li class="nav-item mb-2">
        <a class="nav-link text-white {{ request()->routeIs('materials.*') ? 'active-menu' : '' }}"
            href="{{ route('materials.index') }}">
            <i class="fa-solid fa-boxes-stacked"></i> Materials
        </a>
    </li>

    

    <li class="nav-item mb-2">
        <a class="nav-link text-white {{ request()->routeIs('inventory.index') ? 'active-menu' : '' }}"
           href="{{ route('inventory.index') }}">
           <i class="fa-solid fa-warehouse"></i> Inventory
        </a>
    </li>

    <hr class="text-secondary">

    {{-- Workers --}}
    <h6 class="text-secondary">Workers</h6>

    <li class="nav-item mb-2">
        <a class="nav-link text-white {{ request()->routeIs('workers.index') ? 'active-menu' : '' }}"
           href="{{ route('workers.index') }}">
           <i class="fa-solid fa-users"></i> Workers
        </a>
    </li>

    

    <li class="nav-item mb-2">
        <a class="nav-link text-white {{ request()->routeIs('workers.monthly') ? 'active-menu' : '' }}"
           href="{{ route('workers.monthly') }}">
           <i class="fa-solid fa-file-invoice"></i> Monthly Salary Report
        </a>
    </li>

    <hr class="text-secondary">

    {{-- Reports (Optional future step) --}}
    <h6 class="text-secondary">Reports</h6>

    <li class="nav-item mb-2">
        <a class="nav-link text-white {{ request()->routeIs('report.product.monthly') ? 'active-menu' : '' }}"
        href="{{ route('report.product.monthly') }}">
        <i class="fa-solid fa-chart-bar"></i> Product Monthly Report
        </a>
    </li>
    <li class="nav-item mb-2">
        <a class="nav-link text-white {{ request()->routeIs('report.stock.ledger') ? 'active-menu' : '' }}"
        href="{{ route('report.stock.ledger') }}">
        <i class="fa-solid fa-book"></i> Stock Ledger Report
        </a>
    </li>


</div>
