<div class="offcanvas-menu-overlay"></div>
<div class="offcanvas-menu-wrapper">
    <div class="canvas-close">
        <i class="fa fa-close"></i>
    </div>
    <div class="canvas-search search-switch">
        <i class="fa fa-search"></i>
    </div>
    <nav class="canvas-menu mobile-menu">
        <ul>
            <li class="{{ request()->routeIs('.index') ? 'active' : '' }}"><a href="{{ route('.index') }}">Trang chủ</a></li>
            <li class="{{ request()->routeIs('.about-us') ? 'active' : '' }}"><a href="{{ route('.about-us') }}">Giới thiệu</a></li>
            <li class="{{ request()->routeIs('.course') ? 'active' : '' }}"><a href="{{ route('.course') }}">Khóa học</a></li>
            <li class="{{ request()->routeIs('.new') ? 'active' : '' }}"><a href="{{ route('.new') }}">Tin tức</a></li>
            <li class="{{ request()->routeIs('.contact') ? 'active' : '' }}"><a href="{{ route('.contact') }}">Liên hệ</a></li>
            @auth('web')
                <li class="{{ request()->routeIs('.user') ? 'active' : '' }}"><a href="{{ route('.user') }}">Học viên</a></li>
            @else
                <li class="{{ request()->routeIs('.login') ? 'active' : '' }}"><a href="{{ route('.login') }}">Đăng nhập</a></li>
            @endauth
        </ul>
    </nav>
    <div id="mobile-menu-wrap"></div>
    <div class="canvas-social">
        <a href="#"><i class="fa fa-facebook"></i></a>
        <a href="#"><i class="fa fa-instagram"></i></a>
        <a href="#"><i class="fa fa-youtube-play"></i></a>
    </div>
</div>

<header class="header-section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3">
                <div class="logo">
                    <a href="{{ route('.index') }}">
                        <img src="{{ asset('client/images/logo.jpg') }}" alt="Taekwondo">
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <nav class="nav-menu">
                    <ul>
                        <li class="{{ request()->routeIs('.index') ? 'active' : '' }}"><a href="{{ route('.index') }}">Trang chủ</a></li>
                        <li class="{{ request()->routeIs('.about-us') ? 'active' : '' }}"><a href="{{ route('.about-us') }}">Giới thiệu</a></li>
                        <li class="{{ request()->routeIs('.course') ? 'active' : '' }}"><a href="{{ route('.course') }}">Khóa học</a></li>
                        <li class="{{ request()->routeIs('.new') ? 'active' : '' }}"><a href="{{ route('.new') }}">Tin tức</a></li>
                        <li class="{{ request()->routeIs('.contact') ? 'active' : '' }}"><a href="{{ route('.contact') }}">Liên hệ</a></li>
                        @auth('web')
                            <li class="{{ request()->routeIs('.user') ? 'active' : '' }}"><a href="{{ route('.user') }}">Học viên</a></li>
                        @else
                            <li class="{{ request()->routeIs('.login') ? 'active' : '' }}"><a href="{{ route('.login') }}">Đăng nhập</a></li>
                        @endauth
                    </ul>
                </nav>
            </div>
            <div class="col-lg-3">
                <div class="top-option">
                    <div class="to-search search-switch">
                        <i class="fa fa-search"></i>
                    </div>
                    <div class="to-social">
                        <a href="https://www.facebook.com/profile.php?id=100083353561674" target="_blank" rel="noopener noreferrer"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-tiktok"></i></a>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="canvas-open">
            <i class="fa fa-bars"></i>
        </div>
    </div>
</header>
