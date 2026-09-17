@php
use App\Helpers\BaseHelper;
$adminPrefix = BaseHelper::getAdminPrefix();
$userPrefix = config('core.routes.user.prefix');
@endphp
@extends('layout.backend.backend')
@section('content')
    <div class="content">
        @include('layout.backend.partials.message')

        <div class="block block-rounded p-3">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <i class="ti ti-user-edit me-2"></i>Chỉnh Sửa Võ Sinh
                </h3>
                <div class="block-options">
                    <a href="{{ route($adminPrefix . '.' . $userPrefix . '.index') }}" class="btn btn-sm btn-alt-secondary">
                        <i class="ti ti-list me-1"></i>Danh sách
                    </a>
                </div>
            </div>
            <div class="block-content block-content-full">
                <form action="{{ route($adminPrefix . '.' . $userPrefix . '.update', ['user' => $data->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" value="{{ $data->id }}">

                    {{-- Thông tin cơ bản --}}
                    <div class="mb-4 pb-3 border-bottom">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="ho_va_ten">
                                    Họ và Tên <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('ho_va_ten') is-invalid @enderror"
                                    id="ho_va_ten" name="ho_va_ten"
                                    value="{{ old('ho_va_ten', $data->ho_va_ten) }}"
                                    placeholder="Nhập họ và tên đầy đủ" required>
                                @error('ho_va_ten')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label" for="ngay_thang_nam_sinh">
                                    Ngày Sinh <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control @error('ngay_thang_nam_sinh') is-invalid @enderror"
                                    id="ngay_thang_nam_sinh" name="ngay_thang_nam_sinh"
                                    value="{{ old('ngay_thang_nam_sinh', $data->ngay_thang_nam_sinh ? $data->ngay_thang_nam_sinh->format('Y-m-d') : '') }}" required>
                                @error('ngay_thang_nam_sinh')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label" for="gioi_tinh">
                                    Giới Tính <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('gioi_tinh') is-invalid @enderror"
                                        id="gioi_tinh" name="gioi_tinh" required>
                                    <option value="">-- Chọn --</option>
                                    <option value="Nam" {{ old('gioi_tinh', $data->gioi_tinh) === 'Nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="Nữ" {{ old('gioi_tinh', $data->gioi_tinh) === 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                </select>
                                @error('gioi_tinh')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="ma_hoi_vien">
                                    Mã Hội Viên <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('ma_hoi_vien') is-invalid @enderror"
                                    id="ma_hoi_vien" name="ma_hoi_vien"
                                    value="{{ old('ma_hoi_vien', $data->ma_hoi_vien) }}"
                                    placeholder="VD: HV_nguyenvanA_20240101" required>
                                @error('ma_hoi_vien')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted"><i class="ti ti-info-circle me-1"></i>Mã hội viên phải là duy nhất</small>
                            </div>
                        </div>
                    </div>

                    {{-- Thông tin liên hệ --}}
                    <div class="mb-4 pb-3 border-bottom">
                        <h5 class="mb-3 text-primary">
                            <i class="ti ti-phone me-2"></i>Thông Tin Liên Hệ
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email"
                                    value="{{ old('email', $data->email) }}"
                                    placeholder="example@email.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="phone">Số Điện Thoại</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone"
                                    value="{{ old('phone', $data->phone) }}"
                                    placeholder="0123456789">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label" for="address">Địa Chỉ</label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                        id="address" name="address"
                                        rows="2"
                                        placeholder="Nhập địa chỉ đầy đủ">{{ old('address', $data->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Liên hệ khẩn cấp --}}
                    <div class="mb-4 pb-3 border-bottom">
                        <h5 class="mb-3 text-primary">
                            <i class="ti ti-alert-circle me-2"></i>Liên Hệ Khẩn Cấp
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="emergency_contact_name">Tên Người Liên Hệ</label>
                                <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror"
                                    id="emergency_contact_name" name="emergency_contact_name"
                                    value="{{ old('emergency_contact_name', $data->emergency_contact_name) }}"
                                    placeholder="Tên người liên hệ khẩn cấp">
                                @error('emergency_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="emergency_contact_phone">SĐT Người Liên Hệ</label>
                                <input type="text" class="form-control @error('emergency_contact_phone') is-invalid @enderror"
                                    id="emergency_contact_phone" name="emergency_contact_phone"
                                    value="{{ old('emergency_contact_phone', $data->emergency_contact_phone) }}"
                                    placeholder="0123456789">
                                @error('emergency_contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- CLB & cấp đai --}}
                    <div class="mb-4 pb-3 border-bottom">
                        <h5 class="mb-3 text-primary">
                            <i class="ti ti-building me-2"></i>Thông Tin CLB & Cấp Đai
                        </h5>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label" for="ma_clb">
                                    Mã Câu Lạc Bộ <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('ma_clb') is-invalid @enderror"
                                    id="ma_clb" name="ma_clb"
                                    value="{{ old('ma_clb', $data->ma_clb) }}"
                                    placeholder="VD: CLB_00468" required>
                                @error('ma_clb')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label" for="ma_don_vi">
                                    Mã Đơn Vị <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('ma_don_vi') is-invalid @enderror"
                                    id="ma_don_vi" name="ma_don_vi"
                                    value="{{ old('ma_don_vi', $data->ma_don_vi) }}"
                                    placeholder="VD: DNAI" required>
                                @error('ma_don_vi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label" for="quyen_so">
                                    Quyền Số <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('quyen_so') is-invalid @enderror"
                                    id="quyen_so" name="quyen_so"
                                    value="{{ old('quyen_so', $data->quyen_so) }}"
                                    placeholder="VD: 7" min="1" required>
                                @error('quyen_so')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label" for="cap_dai_id">
                                    Cấp Đai <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('cap_dai_id') is-invalid @enderror"
                                        id="cap_dai_id" name="cap_dai_id" required>
                                    <option value="">-- Chọn cấp đai --</option>
                                    @foreach($capDai ?? [] as $capDaiItem)
                                        <option value="{{ $capDaiItem->id }}" {{ (string) old('cap_dai_id', $data->cap_dai_id) === (string) $capDaiItem->id ? 'selected' : '' }}>
                                            {{ $capDaiItem->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cap_dai_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Mật khẩu (tuỳ chọn) --}}
                    <div class="mb-4 pb-3 border-bottom">
                        <h5 class="mb-3 text-primary">
                            <i class="ti ti-key me-2"></i>Đổi Mật Khẩu
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="password">Mật khẩu mới</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password"
                                    value=""
                                    autocomplete="new-password"
                                    placeholder="Để trống nếu giữ nguyên mật khẩu">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Chỉ nhập khi cần đổi mật khẩu đăng nhập.</small>
                            </div>
                        </div>
                    </div>

                    {{-- Trạng thái --}}
                    <div class="mb-4">
                        <h5 class="mb-3 text-primary">
                            <i class="ti ti-toggle-left me-2"></i>Trạng Thái
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="active_status">Trạng Thái Hoạt Động</label>
                                <select class="form-select @error('active_status') is-invalid @enderror"
                                        id="active_status" name="active_status">
                                    @foreach($vo_sinhStatus ?? [] as $key => $status)
                                        <option value="{{ $key ? 1 : 0 }}" {{ (string) old('active_status', $data->active_status ? 1 : 0) === (string) ($key ? 1 : 0) ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('active_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route($adminPrefix . '.' . $userPrefix . '.index') }}" class="btn btn-alt-secondary">
                                    <i class="ti ti-x me-1"></i>Hủy
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-check me-1"></i>Cập nhật
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
