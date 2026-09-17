@extends('layout.frontend.frontend')
@section('content')
<section class="breadcrumb-section set-bg" data-setbg="{{ asset('client/gymlife/img/breadcrumb-bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb-text">
                    <h2>Liên hệ</h2>
                    <div class="bt-option">
                        <a href="{{ route($userPrefix . '.index') }}">Trang chủ</a>
                        <span>Liên hệ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="contact-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="leave-comment">
                    <h4>Gửi tin nhắn cho chúng tôi</h4>
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form method="POST" action="{{ route($userPrefix . '.contact.submit') }}">
                        @csrf
                        <input type="text" id="name" name="name" placeholder="Họ và tên *" value="{{ old('name') }}" required>
                        @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        <input type="email" id="email" name="email" placeholder="Email *" value="{{ old('email') }}" required>
                        @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        <input type="tel" id="phone" name="phone" placeholder="Số điện thoại *" value="{{ old('phone') }}" required>
                        @error('phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        <input type="text" id="subject" name="subject" placeholder="Chủ đề *" value="{{ old('subject') }}" required>
                        @error('subject')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        <textarea id="message" name="message" placeholder="Tin nhắn *" required>{{ old('message') }}</textarea>
                        @error('message')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        <button type="submit">Gửi liên hệ</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="leave-comment">
                    <h4>Thông tin liên hệ</h4>
                    <ul class="class-timetable details-timetable">
                        <li><span>Địa chỉ:</span><p>123 Đường ABC, Quận 1, TP.HCM</p></li>
                        <li><span>Điện thoại:</span><p>0901 234 567</p></li>
                        <li><span>Email:</span><p>{{ config('club.contact_email') }}</p></li>
                        <li><span>Giờ làm việc:</span><p>06:00 - 21:00 mỗi ngày</p></li>
                    </ul>
                    <h4 class="mt-5">Câu hỏi thường gặp</h4>
                    <div class="faq-item"><div class="faq-question" onclick="toggleFaq(1)">Lớp học có phù hợp cho người mới?</div><div class="faq-answer" id="faq-1"><p class="faq-answer-text">Có, chúng tôi có chương trình riêng cho người mới bắt đầu.</p></div></div>
                    <div class="faq-item"><div class="faq-question" onclick="toggleFaq(2)">Trẻ em từ mấy tuổi có thể tham gia?</div><div class="faq-answer" id="faq-2"><p class="faq-answer-text">Trẻ em từ 5 tuổi trở lên có thể đăng ký lớp cơ bản.</p></div></div>
                    <div class="faq-item"><div class="faq-question" onclick="toggleFaq(3)">Có lớp học thử không?</div><div class="faq-answer" id="faq-3"><p class="faq-answer-text">Có, CLB có buổi học thử để học viên trải nghiệm.</p></div></div>
                                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function toggleFaq(id) {
        const faqAnswer = document.getElementById('faq-' + id);
        const faqQuestion = faqAnswer ? faqAnswer.previousElementSibling : null;
        if (!faqAnswer || !faqQuestion) return;
        
        document.querySelectorAll('.faq-answer').forEach(answer => {
            if (answer.id !== 'faq-' + id) {
                answer.classList.remove('active');
                answer.previousElementSibling.classList.remove('active');
            }
        });
        
        faqAnswer.classList.toggle('active');
        faqQuestion.classList.toggle('active');
    }
</script>
@endsection
