<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
     id="layout-navbar">

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="#" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ Auth::user()->photo ? Storage::url('user/' . Auth::user()->photo) : asset('default.png') }}"
                             class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
<img src="{{ Auth::user()->photo 
    ? Storage::url('user/' . Auth::user()->photo) 
    : asset('default.png') }}"
    class="w-px-40 h-auto rounded-circle">

                                    </div>
                                </div>

                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ Auth::user()->user_name }}</span>
                                    <small class="text-muted">{{ Auth::user()->role }}</small>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="{{ route('profile.index') }}">
                            <i class="bx bx-user me-2"></i> Setting
                        </a>
                    </li>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="bx bx-power-off me-2"></i> Keluar
                        </button>
                    </form>
                </ul>
            </li>

        </ul>

    </div>
</nav>
