<div class="gettouch-section">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="gt-text">
                    <i class="fa fa-map-marker"></i>
                    <p>CLB Taekwondo Đồng Phú,<br>Thôn 9 Xã Đồng Phú</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="gt-text">
                    <i class="fa fa-mobile"></i>
                    <ul>
                        <li>028 2238 6668</li>
                        <li>0900 000 000</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="gt-text email">
                    <i class="fa fa-envelope"></i>
                    <p>taekwondo@gmail.com</p>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="footer-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="fs-about">
                    <div class="fa-logo">
                        <a href="{{ route('.index') }}"><img src="{{ asset('client/images/logo.jpg') }}" alt="Taekwondo"></a>
                    </div>
                    <p>Môi trường tập luyện kỷ luật, tích cực và an toàn cho học viên ở mọi lứa tuổi.</p>
                    <div class="fa-social">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                        <a href="#"><i class="fa fa-youtube-play"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="fs-widget">
                    <h4>Liên kết nhanh</h4>
                    <ul>
                        <li><a href="{{ route('.about-us') }}">Giới thiệu</a></li>
                        <li><a href="{{ route('.course') }}">Khóa học</a></li>
                        <li><a href="{{ route('.new') }}">Tin tức</a></li>
                        <li><a href="{{ route('.contact') }}">Liên hệ</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="fs-widget">
                    <h4>Hỗ trợ</h4>
                    <ul>
                        @auth('web')
                            <li><a href="{{ route('.user') }}">Trang học viên</a></li>
                        @else
                            <li><a href="{{ route('.login') }}">Đăng nhập</a></li>
                        @endauth
                        <li><a href="{{ route('.contact') }}">Gửi liên hệ</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="copyright-text">
                    <p>Copyright &copy; {{ now()->year }} Taekwondo Đồng Phú. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="search-model">
    <div class="h-100 d-flex align-items-center justify-content-center">
        <div class="search-close-switch">+</div>
        <form class="search-model-form">
            <input type="text" id="search-input" placeholder="Tìm kiếm...">
        </form>
    </div>
</div>
