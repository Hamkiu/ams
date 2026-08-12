<header class="top-header">
    <nav class="navbar navbar-expand align-items-center gap-4">
        <div class="btn-toggle">
            <a href="javascript:;"><i class="material-icons-outlined">menu</i></a>
        </div>
        <div class="search-bar flex-grow-1 d-none">
            <div class="position-relative">
                <input class="form-control rounded-5 px-5 search-control d-lg-block d-none" type="text"
                    placeholder="Search">
                <span
                    class="material-icons-outlined position-absolute d-lg-block d-none ms-3 translate-middle-y start-0 top-50">search</span>
                <span
                    class="material-icons-outlined position-absolute me-3 translate-middle-y end-0 top-50 search-close">close</span>
                <div class="search-popup p-3">
                    <div class="card rounded-4 overflow-hidden">
                        <div class="card-header d-lg-none">
                            <div class="position-relative">
                                <input class="form-control rounded-5 px-5 mobile-search-control" type="text"
                                    placeholder="Search">
                                <span
                                    class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50">search</span>
                                <span
                                    class="material-icons-outlined position-absolute me-3 translate-middle-y end-0 top-50 mobile-search-close">close</span>
                            </div>
                        </div>
                        <div class="card-body search-content">

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <ul class="navbar-nav gap-1 nav-right-links align-items-center ms-auto">

            <li class="nav-item dropdown d-none">
                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                    data-bs-auto-close="outside" data-bs-toggle="dropdown" href="javascript:;"><i
                        class="material-icons-outlined">notifications</i>
                    <span class="badge-notify">5</span>
                </a>
                <div class="dropdown-menu dropdown-notify dropdown-menu-end shadow">
                    <div class="px-3 py-1 d-flex align-items-center justify-content-between border-bottom">
                        <h5 class="notiy-title mb-0">Notifications</h5>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle dropdown-toggle-nocaret option"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="material-icons-outlined">
                                    more_vert
                                </span>
                            </button>

                        </div>
                    </div>
                    <div class="notify-list">

                    </div>
                </div>
            </li>

            <li class="nav-item dropdown">

                <a href="javascript:;" class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown"
                    aria-expanded="false">

                    <img src="{{ asset('template/assets/images/logo-mbi.png') }}" class="rounded-circle p-1 border"
                        width="45" height="45" alt="User">

                </a>

                <div class="dropdown-menu dropdown-user dropdown-menu-end shadow" style="width: 320px;">

                    {{-- USER INFORMATION --}}
                    <div class="px-4 py-4 text-center">

                        <img src="{{ asset('template/assets/images/logo-mbi.png') }}"
                            class="rounded-circle p-1 shadow mb-3" width="85" height="85" alt="User">

                        {{-- Nama --}}
                        <h5 class="fw-bold mb-1 text-wrap" style="line-height: 1.4;">

                            {{ Auth::user()->name }}

                        </h5>

                        {{-- Role --}}
                        <div class="text-muted small">
                            {{ Auth::user()->getRoleNames()->first() }}
                        </div>

                    </div>

                    <hr class="dropdown-divider my-0">

                    {{-- LOGOUT --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <a class="dropdown-item d-flex align-items-center gap-2 py-3 px-4" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); this.closest('form').submit();">

                            <i class="material-icons-outlined">
                                power_settings_new
                            </i>

                            <span>Logout</span>

                        </a>

                    </form>

                </div>

            </li>
        </ul>

    </nav>
</header>
