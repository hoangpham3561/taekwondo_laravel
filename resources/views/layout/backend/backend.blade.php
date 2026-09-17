@php
    use App\Helpers\BaseHelper;
    $adminPrefix = BaseHelper::getAdminPrefix();
    $adminUser = Auth::guard('admin')->user();
    $avatarUrl = $adminUser && $adminUser->photo_url
        ? $adminUser->photo_url . '?v=' . time()
        : asset('assets/images/faces/face15.jpg');
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Admin') | Taekwondo</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.26.0/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/jvectormap/jquery-jvectormap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/owl-carousel-2/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/owl-carousel-2/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-ticket-edit.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    @stack('css_after')
</head>
<body>
<div class="container-scroller">
    @include('layout.backend.partials.sidebar')

    <div class="container-fluid page-body-wrapper">
        <nav class="navbar p-0 fixed-top d-flex flex-row">
            <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
                <a class="navbar-brand brand-logo-mini" href="{{ route($adminPrefix . '.dashboard') }}">
                    <img src="{{ asset('assets/images/logo-mini.svg') }}" alt="logo" />
                </a>
            </div>
            <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button>

                <ul class="navbar-nav w-100">
                    <li class="nav-item w-100">
                        <form class="nav-link mt-2 mt-md-0 d-none d-lg-flex search">
                            <input type="text" class="form-control" placeholder="Search">
                        </form>
                    </li>
                </ul>

                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item dropdown d-none d-lg-flex align-items-center me-2">
                        <a class="nav-link position-relative px-2" href="#" id="messageDropdown" data-toggle="dropdown" aria-expanded="false" title="Tin nhắn">
                            <i class="mdi mdi-email fs-5 header-shortcut-icon"></i>
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-dark rounded-circle"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                            <h6 class="p-3 mb-0">Tin nhắn</h6>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item" href="{{ route($adminPrefix . '.messages.index') }}">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-message-text text-success"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject mb-1">Quản trị hệ thống</p>
                                    <p class="text-muted ellipsis mb-0">Bạn có 1 tin nhắn mới.</p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center preview-item" href="{{ route($adminPrefix . '.messages.index') }}">
                                See all messages
                            </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown d-none d-lg-flex align-items-center me-2">
                        <a class="nav-link position-relative px-2" href="#" id="notificationDropdown" data-toggle="dropdown" aria-expanded="false" title="Thông báo">
                            <i class="mdi mdi-bell fs-5 header-shortcut-icon"></i>
                            @if(($adminUnreadNotificationCount ?? 0) > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; min-width: 1.1rem;">{{ $adminUnreadNotificationCount > 9 ? '9+' : $adminUnreadNotificationCount }}</span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                            <h6 class="p-3 mb-0">Thông báo</h6>
                            <div class="dropdown-divider"></div>
                            @forelse($adminNotificationPreview ?? [] as $n)
                                @php
                                    $payload = is_array($n->data) ? $n->data : [];
                                    $nTitle = $payload['title'] ?? 'Thông báo';
                                    $nBody = $payload['body'] ?? '';
                                    $isUnread = $n->read_at === null;
                                @endphp
                                <a class="dropdown-item preview-item {{ $isUnread ? 'bg-light' : '' }}" href="{{ route($adminPrefix . '.notifications.open', $n->id) }}">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-dark rounded-circle">
                                            <i class="mdi mdi-bell-ring {{ $isUnread ? 'text-danger' : 'text-secondary' }}"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content">
                                        <p class="preview-subject mb-1">{{ \Illuminate\Support\Str::limit($nTitle, 48) }}</p>
                                        <p class="text-muted ellipsis mb-0 small">{{ \Illuminate\Support\Str::limit($nBody, 80) }}</p>
                                    </div>
                                </a>
                            @empty
                                <p class="px-3 py-2 mb-0 text-muted small">Chưa có thông báo.</p>
                            @endforelse
                            <div class="dropdown-divider"></div>
                            <div class="px-3 py-2">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <a class="small text-primary mb-0" href="{{ route($adminPrefix . '.notifications.index') }}">
                                        Xem tất cả thông báo
                                    </a>
                                    <form action="{{ route($adminPrefix . '.notifications.read_all') }}" method="POST" class="mb-0 ms-auto">
                                        @csrf
                                        <button type="submit" class="btn btn-link btn-sm text-muted p-0 small text-end text-decoration-none">
                                            Đánh dấu đã đọc tất cả
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item d-none d-lg-flex align-items-center me-2">
                        <button type="button" id="themeModeToggle" class="btn btn-sm btn-alt-secondary" title="Đổi chế độ sáng/tối">
                            <i class="mdi mdi-weather-night"></i>
                        </button>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
                            <div class="navbar-profile">
                                <img class="img-xs rounded-circle" src="{{ $avatarUrl }}" alt="">
                                <p class="mb-0 d-none d-sm-block navbar-profile-name">{{ $adminUser->ho_va_ten ?? 'Admin' }}</p>
                                <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
                            <h6 class="p-3 mb-0">Tài khoản</h6>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item" href="{{ route($adminPrefix . '.profile-setting.index') }}">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-account text-success"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject mb-1">Hồ sơ</p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item" href="{{ route($adminPrefix . '.logout') }}">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-logout text-danger"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject mb-1">Đăng xuất</p>
                                </div>
                            </a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="mdi mdi-format-line-spacing"></span>
                </button>
            </div>
        </nav>

        <div class="main-panel">
            <div class="content-wrapper">
                @yield('content')
            </div>
            <footer class="footer">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                    <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">
                        Copyright © {{ date('Y') }} Taekwondo
                    </span>
                    <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">
                        Admin by Taekwondo-Đồng_Phú
                    </span>
                </div>
            </footer>
        </div>
    </div>
</div>

<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
<script src="{{ asset('assets/vendors/jvectormap/jquery-jvectormap.min.js') }}"></script>
<script src="{{ asset('assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ asset('assets/vendors/owl-carousel-2/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('assets/js/misc.js') }}"></script>
<script src="{{ asset('assets/js/settings.js') }}"></script>
<script src="{{ asset('assets/js/todolist.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script>
    (function () {
        const storageKey = 'admin-theme-mode';
        const body = document.body;
        const toggleBtn = document.getElementById('themeModeToggle');

        function applyTheme(mode) {
            if (mode === 'light') {
                body.classList.add('theme-light');
            } else {
                body.classList.remove('theme-light');
            }

            if (toggleBtn) {
                toggleBtn.innerHTML = mode === 'light'
                    ? '<i class="mdi mdi-weather-sunny"></i>'
                    : '<i class="mdi mdi-weather-night"></i>';
            }
        }

        const savedMode = localStorage.getItem(storageKey) || 'dark';
        applyTheme(savedMode);

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                const nextMode = body.classList.contains('theme-light') ? 'dark' : 'light';
                localStorage.setItem(storageKey, nextMode);
                applyTheme(nextMode);
            });
        }
    })();
</script>
@stack('js_after')
@stack('scripts')
</body>
</html>

