@extends('layout.frontend.office')
@section('content')
<section class="office-content-wrapper">
    <div class="office">
        <div class="office-main-wrapper container mt-5 mt-lg-0">
            <div class="office-main">
                <h3 class="office-title px-2 d-flex justify-content-between align-items-center">
                    THÔNG TIN HỒ SƠ
                    <div class="office-mobile-toggle d-lg-none">
                        <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                            <img src="{{ asset('client/images/launchpad/arrow-down.svg') }}" alt="icon">
                        </button>
                    </div>
                </h3>
                <div class="office-card">
                    <form id="profileForm" action="{{ route($userPrefix . '.updateProfile') }}" method="POST" enctype="multipart/form-data" class="form-default">
                        @csrf
                        <div class="row g-4 mx-0 mt-1">
                            <div class="col-lg-4 col-md-5">
                                <div class="text-center box-info-user">
                                    <div class="office-card-tiltle">1. Thay đổi ảnh đại diện</div>
                                    <div class="office-avatar">
                                        <img class="office-avatar-img img-bill"
                                            id="avatarPreview"
                                            src="{{ (empty($user->Avatar) || $user->Avatar == 'avatar.png') ? asset('client/images/avatar2.png') : asset($user->Avatar) }}"
                                            alt="Avatar">
                                    </div>
                                    <div class="box-upload">
                                        <input id="imgInp" type="file" accept="image/*" name="avatar" hidden>
                                        <label class="btn btn-outline-upload mt-3" for="imgInp">Chọn Ảnh</label>
                                    </div>
                                    <p class="mt-3 mb-0 small">Dung lượng file tối đa 1 MB</p>
                                    <p class="mt-1 small">Định dạng: JPEG, PNG</p>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-7">
                                <div class="office-card-tiltle">2. Thông tin cơ bản</div>
                                <div class="mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <input class="form-control form-office-card"
                                        id="email"
                                        type="email"
                                        value="{{ $user->Email ?? '' }}"
                                        readonly
                                        style="background-color: #f5f5f5; cursor: not-allowed;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="fullName">Họ & Tên <span class="text-danger">*</span></label>
                                    <input class="form-control form-office-card"
                                        id="fullName"
                                        name="fullName"
                                        type="text"
                                        placeholder="Nhập họ tên"
                                        value="{{ old('fullName', $user->FullName ?? '') }}"
                                        required>
                                    @error('fullName')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="CMND">CCCD/CMND <span class="text-danger">*</span></label>
                                    <input class="form-control form-office-card"
                                        id="CMND"
                                        name="CMND"
                                        type="text"
                                        placeholder="Nhập CCCD/CMND"
                                        value="{{ old('CMND', $user->CMND ?? '') }}"
                                        required>
                                    @error('CMND')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="phone">Điện thoại <span class="text-danger">*</span></label>
                                    <input class="form-control form-office-card"
                                        id="phone"
                                        name="phone"
                                        type="tel"
                                        placeholder="Nhập số điện thoại"
                                        value="{{ old('phone', $user->Phone ?? $user->Phone ?? '') }}">
                                    @error('phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="phone">Ngày sinh <span class="text-danger">*</span></label>
                                    <input class="form-control form-office-card"
                                        id="birthday"
                                        name="birthday"
                                        type="date"
                                        placeholder="Nhập ngày sinh"
                                        value="{{ old('birthday', $user->Birthday ?? '') }}">
                                    @error('birthday')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="phone">Địa chỉ <span class="text-danger">*</span></label>
                                    <input class="form-control form-office-card"
                                        id="address"
                                        name="address"
                                        type="text"
                                        placeholder="Nhập địa chỉ"
                                        value="{{ old('address', $user->Address ?? '') }}">
                                    @error('address')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-grid">
                                    <button class="btn office-submit" type="submit">CẬP NHẬT</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="office-card mt-4">
                    <form id="bankForm" action="{{ route($userPrefix . '.updateBankInfo') }}" method="POST" class="form-default">
                        @csrf
                        <div class="office-card-tiltle mb-3">3. Thông tin tài khoản ngân hàng</div>
                        <div class="row g-4 mx-0 mt-1">
                            <div class="col-lg-4 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="bank_name">Tên ngân hàng <span class="text-danger">*</span></label>
                                    <input class="form-control form-office-card"
                                        id="bank_name"
                                        name="bank_name"
                                        type="text"
                                        placeholder="Ví dụ: Vietcombank, Techcombank, BIDV..."
                                        value="{{ old('bank_name', $user->NganHang ?? '') }}"
                                        required>
                                    @error('bank_name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="account_number">Số tài khoản <span class="text-danger">*</span></label>
                                    <input class="form-control form-office-card"
                                        id="account_number"
                                        name="account_number"
                                        type="text"
                                        placeholder="Nhập số tài khoản ngân hàng"
                                        value="{{ old('account_number', $user->STK ?? '') }}"
                                        required>
                                    @error('account_number')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="account_name">Tên chủ tài khoản <span class="text-danger">*</span></label>
                                    <input class="form-control form-office-card"
                                        id="account_name"
                                        name="account_name"
                                        type="text"
                                        placeholder="Nhập tên chủ tài khoản"
                                        value="{{ old('account_name', $user->Ten_TK ?? '') }}"
                                        required>
                                    @error('account_name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="d-grid mt-3">
                            <button class="btn office-submit" type="submit">CẬP NHẬT THÔNG TIN NGÂN HÀNG</button>
                        </div>
                    </form>
                </div>

                {{-- ✅ Form đổi email --}}
                <div class="office-card mt-4">
                    <form id="changeEmailForm" action="{{ route($userPrefix . '.requestChangeEmail') }}" method="POST" class="form-default">
                        @csrf
                        <div class="office-card-tiltle mb-3">
                            4. Thay đổi địa chỉ email
                        </div>

                        <div class="row g-4 mx-0 mt-1">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="current_email">Email hiện tại</label>
                                    <input class="form-control form-office-card"
                                        id="current_email"
                                        type="email"
                                        value="{{ $user->Email }}"
                                        readonly
                                        style="background-color: #f5f5f5; cursor: not-allowed;">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="new_email">
                                        Email mới <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control form-office-card @error('new_email') is-invalid @enderror"
                                        id="new_email"
                                        name="new_email"
                                        type="email"
                                        placeholder="Nhập email mới"
                                        value="{{ old('new_email') }}"
                                        required>
                                    @error('new_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-info-circle me-2"></i>Lưu ý khi đổi email:
                            </h6>
                            <ul class="mb-0 small">
                                <li>Chúng tôi sẽ gửi email xác nhận đến <strong>email hiện tại</strong> của bạn</li>
                                <li>Bạn cần xác nhận qua link trong email để hoàn tất việc đổi email</li>
                                <li>Link xác nhận có hiệu lực trong 24 giờ</li>
                            </ul>
                        </div>

                        <div class="d-grid mt-3">
                            <button class="btn office-submit" type="submit" >
                                <i class="fas fa-paper-plane me-2"></i>GỬI YÊU CẦU ĐỔI EMAIL
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function readPath(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Kiểm tra kích thước file (1MB = 1048576 bytes)
            if (file.size > 1048576) {
                alert('Dung lượng file vượt quá 1 MB. Vui lòng chọn file khác.');
                $(input).val('');
                return;
            }

            // Kiểm tra định dạng file
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!validTypes.includes(file.type)) {
                alert('Chỉ chấp nhận file JPEG, PNG, JPG. Vui lòng chọn file khác.');
                $(input).val('');
                return;
            }

            // Hiển thị preview
            const reader = new FileReader();
            reader.onload = function(e) {
                $('.img-bill').attr('src', e.target.result);
                $('#avatarPreview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    }

    $(document).ready(function() {
        // Preview avatar khi chọn file
        $('#imgInp').on('change', function() {
            readPath(this);
        });
    });

    // Validate email form before submit
    $('#changeEmailForm').on('submit', function(e) {
        const currentEmail = $('#current_email').val();
        const newEmail = $('#new_email').val();

        if (currentEmail === newEmail) {
            e.preventDefault();
            alert('Email mới phải khác email hiện tại!');
            return false;
        }

        if (!confirm('Bạn có chắc muốn đổi email? Email xác nhận sẽ được gửi đến email hiện tại của bạn.')) {
            e.preventDefault();
            return false;
        }
    });

</script>
@endpush
@endsection
