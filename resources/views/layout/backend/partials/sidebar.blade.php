@php
use App\Helpers\BaseHelper;
$adminPrefix = BaseHelper::getAdminPrefix();
$userPrefix = config('core.routes.user.prefix');
$newsPrefix = config('core.routes.news.prefix');
$huanLuyenVienPrefix = config('core.routes.huan_luyen_vien.prefix', 'huan-luyen-vien');
$chiNhanhPrefix = config('core.routes.chi_nhanh.prefix', 'chi-nhanh');
$cauLacBoPrefix = config('core.routes.cau_lac_bo.prefix', 'caulacbo');
$khoaHocPrefix = config('core.routes.khoa_hoc.prefix', 'khoahoc');
$baiQuyenPrefix = config('core.routes.bai_quyen.prefix', 'bai-quyen');
$capDaiPrefix = config('core.routes.cap_dai.prefix', 'cap-dai');
@endphp

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
        <a class="sidebar-brand brand-logo" href="{{ route($adminPrefix . '.dashboard') }}">
            <img src="{{ asset('assets/images/logo.jpg') }}" alt="logo" class="admin-sidebar-logo" />
        </a>
        <a class="sidebar-brand brand-logo-mini" href="{{ route($adminPrefix . '.dashboard') }}">
            <img src="{{ asset('assets/images/logo.jpg') }}" alt="logo" class="admin-sidebar-logo admin-sidebar-logo-mini" />
        </a>
    </div>
    <ul class="nav">
        <li class="nav-item nav-category">
            <span class="nav-link">Navigation</span>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route($adminPrefix . '.dashboard') }}">
                <span class="menu-icon"><i class="mdi mdi-speedometer"></i></span>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route($adminPrefix . '.' . $userPrefix . '.index') }}">
                <span class="menu-icon"><i class="mdi mdi-account-multiple"></i></span>
                <span class="menu-title">Võ sinh</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route($adminPrefix . '.' . $newsPrefix . '.index') }}">
                <span class="menu-icon"><i class="mdi mdi-newspaper"></i></span>
                <span class="menu-title">Tin tức</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route($adminPrefix . '.' . $huanLuyenVienPrefix . '.index') }}">
                <span class="menu-icon"><i class="mdi mdi-account-tie"></i></span>
                <span class="menu-title">Huấn luyện viên</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route($adminPrefix . '.' . $cauLacBoPrefix . '.index') }}">
                <span class="menu-icon"><i class="mdi mdi-home-city"></i></span>
                <span class="menu-title">Câu lạc bộ</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route($adminPrefix . '.' . $chiNhanhPrefix . '.index') }}">
                <span class="menu-icon"><i class="mdi mdi-map-marker"></i></span>
                <span class="menu-title">Đơn vị</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route($adminPrefix . '.' . $khoaHocPrefix . '.index') }}">
                <span class="menu-icon"><i class="mdi mdi-school"></i></span>
                <span class="menu-title">Khóa học</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route($adminPrefix . '.' . $baiQuyenPrefix . '.index') }}">
                <span class="menu-icon"><i class="mdi mdi-run"></i></span>
                <span class="menu-title">Bài quyền</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route($adminPrefix . '.' . $capDaiPrefix . '.index') }}">
                <span class="menu-icon"><i class="mdi mdi-medal"></i></span>
                <span class="menu-title">Cấp đai</span>
            </a>
        </li>
    </ul>
</nav>

