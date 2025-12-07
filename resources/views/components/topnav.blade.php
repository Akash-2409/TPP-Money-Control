<nav class="navbar navbar-light bg-white border-bottom mb-3" style="margin-left: 240px;">
    <div class="container-fluid">

        <h4 class="mt-2">Dashboard</h4>

        <div class="d-flex align-items-center">

            <span class="me-3 fw-bold">{{ auth()->user()->name }}</span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-danger btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>
