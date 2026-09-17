@php
use App\Helpers\BaseHelper;
$adminPrefix = BaseHelper::getAdminPrefix();
$huanLuyenVienPrefix = config('core.routes.huan_luyen_vien.prefix');
@endphp
@extends('layout.backend.backend')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-2">
                        Chỉnh Sửa Huấn Luyện Viên
                    </h1>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route($adminPrefix . '.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route($adminPrefix . '.' . $huanLuyenVienPrefix . '.index') }}">Huấn Luyện Viên</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Chỉnh Sửa
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
        @include('layout.backend.partials.message')
        
        <div class="block block-rounded p-3">
            <div class="block-header block-header-default">
                <h3 class="block-title">Thông Tin Huấn Luyện Viên</h3>
            </div>
            <div class="block-content block-content-full">
                <form action="{{ route($adminPrefix . '.' . $huanLuyenVienPrefix . '.update', $data->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="ma_hoi_vien">
                                Mã Hội Viên <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('ma_hoi_vien') is-invalid @enderror" 
                                id="ma_hoi_vien" name="ma_hoi_vien" 
                                value="{{ old('ma_hoi_vien', $data->ma_hoi_vien) }}" 
                                placeholder="Nhập mã hội viên" required>
                            @error('ma_hoi_vien')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

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

                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="ngay_thang_nam_sinh">
                                Ngày Sinh <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control @error('ngay_thang_nam_sinh') is-invalid @enderror" 
                                id="ngay_thang_nam_sinh" name="ngay_thang_nam_sinh" 
                                value="{{ old('ngay_thang_nam_sinh', $data->ngay_thang_nam_sinh) }}" required>
                            @error('ngay_thang_nam_sinh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="gioi_tinh">
                                Giới Tính <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('gioi_tinh') is-invalid @enderror" 
                                id="gioi_tinh" name="gioi_tinh" required>
                                <option value="">-- Chọn Giới Tính --</option>
                                <option value="Nam" {{ old('gioi_tinh', $data->gioi_tinh) == 'Nam' ? 'selected' : '' }}>Nam</option>
                                <option value="Nữ" {{ old('gioi_tinh', $data->gioi_tinh) == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                            </select>
                            @error('gioi_tinh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="cap_dai_id">
                                Cấp Đai <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('cap_dai_id') is-invalid @enderror" 
                                id="cap_dai_id" name="cap_dai_id" required>
                                <option value="">-- Chọn Cấp Đai --</option>
                                @foreach($capDai ?? [] as $capDaiItem)
                                    <option value="{{ $capDaiItem->id }}" {{ old('cap_dai_id', $data->cap_dai_id) == $capDaiItem->id ? 'selected' : '' }}>
                                        {{ $capDaiItem->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cap_dai_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="ma_clb">
                                Mã Câu Lạc Bộ
                            </label>
                            <input type="text" class="form-control @error('ma_clb') is-invalid @enderror" 
                                id="ma_clb" name="ma_clb" 
                                value="{{ old('ma_clb', $data->ma_clb) }}" 
                                placeholder="Nhập mã câu lạc bộ">
                            @error('ma_clb')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="ma_don_vi">
                                Mã Đơn Vị <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('ma_don_vi') is-invalid @enderror" 
                                id="ma_don_vi" name="ma_don_vi" 
                                value="{{ old('ma_don_vi', $data->ma_don_vi) }}" 
                                placeholder="Nhập mã đơn vị" required>
                            @error('ma_don_vi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="quyen_so">
                                Quyền Số
                            </label>
                            <input type="number" class="form-control @error('quyen_so') is-invalid @enderror" 
                                id="quyen_so" name="quyen_so" 
                                value="{{ old('quyen_so', $data->quyen_so) }}" 
                                placeholder="Nhập quyền số">
                            @error('quyen_so')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="phone">
                                Điện Thoại
                            </label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                id="phone" name="phone" 
                                value="{{ old('phone', $data->phone) }}" 
                                placeholder="Nhập số điện thoại">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="email">
                                Email
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                id="email" name="email" 
                                value="{{ old('email', $data->email) }}" 
                                placeholder="Nhập email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="address">
                                Địa Chỉ
                            </label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                id="address" name="address" 
                                rows="3" 
                                placeholder="Nhập địa chỉ">{{ old('address', $data->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="role">
                                Vai Trò <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('role') is-invalid @enderror" 
                                id="role" name="role" required>
                                <option value="admin" {{ old('role', $data->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="owner" {{ old('role', $data->role) == 'owner' ? 'selected' : '' }}>Owner</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="experience_years">
                                Số Năm Kinh Nghiệm
                            </label>
                            <input type="number" class="form-control @error('experience_years') is-invalid @enderror" 
                                id="experience_years" name="experience_years" 
                                value="{{ old('experience_years', $data->experience_years) }}" 
                                placeholder="Nhập số năm kinh nghiệm" min="0">
                            @error('experience_years')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="is_active">
                                Trạng Thái
                            </label>
                            <select class="form-select @error('is_active') is-invalid @enderror" 
                                id="is_active" name="is_active">
                                <option value="1" {{ old('is_active', $data->is_active) == 1 ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ old('is_active', $data->is_active) == 0 ? 'selected' : '' }}>Ngừng hoạt động</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="specialization">
                                Chuyên Môn
                            </label>
                            <input type="text" class="form-control @error('specialization') is-invalid @enderror" 
                                id="specialization" name="specialization" 
                                value="{{ old('specialization', $data->specialization) }}" 
                                placeholder="Nhập chuyên môn">
                            @error('specialization')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="emergency_contact_name">
                                Tên Người Liên Hệ Khẩn Cấp
                            </label>
                            <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                id="emergency_contact_name" name="emergency_contact_name" 
                                value="{{ old('emergency_contact_name', $data->emergency_contact_name) }}" 
                                placeholder="Nhập tên người liên hệ khẩn cấp">
                            @error('emergency_contact_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="emergency_contact_phone">
                                Số Điện Thoại Liên Hệ Khẩn Cấp
                            </label>
                            <input type="text" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                                id="emergency_contact_phone" name="emergency_contact_phone" 
                                value="{{ old('emergency_contact_phone', $data->emergency_contact_phone) }}" 
                                placeholder="Nhập số điện thoại liên hệ khẩn cấp">
                            @error('emergency_contact_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="bio">
                                Tiểu Sử
                            </label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                id="bio" name="bio" 
                                rows="4" 
                                placeholder="Nhập tiểu sử">{{ old('bio', $data->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i> Cập Nhật
                        </button>
                        <a href="{{ route($adminPrefix . '.' . $huanLuyenVienPrefix . '.index') }}" class="btn btn-alt-secondary">
                            <i class="fa fa-times me-1"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

