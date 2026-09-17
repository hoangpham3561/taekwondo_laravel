@php
use App\Helpers\BaseHelper;
$adminPrefix = BaseHelper::getAdminPrefix();
$userPrefix = config('core.routes.user.prefix');
@endphp
@extends('layout.backend.backend')
@section('content')
    

    <div class="content">
        @include('layout.backend.partials.message')
        
        {{-- Import Section --}}
        <div class="block block-rounded mb-4 p-3">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <i class="ti ti-file-upload me-2"></i>Import Từ File Excel/CSV
                </h3>
            </div>
            <div class="block-content block-content-full">
                {{-- Form import ẩn --}}
                <form id="importForm" action="{{ route($adminPrefix . '.' . $userPrefix . '.import.store') }}" method="POST" enctype="multipart/form-data" style="display: none;">
                    @csrf
                    <input type="file" id="importFileInput" name="file" accept=".xlsx,.xls,.csv" required>
                </form>
                
               
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <a href="javascript:void(0)" id="importFileBtn" class="btn btn-primary">
                        <i class="ti ti-upload me-1"></i>Import File Excel/CSV
                    </a>
                    <a href="{{ route($adminPrefix . '.' . $userPrefix . '.import.template') }}" class="btn btn-alt-info">
                        <i class="ti ti-download me-1"></i>Tải File Mẫu
                    </a>
                </div>
                
                {{-- Show import failures if any --}}
                @if(session('import_failures'))
                    <div class="alert alert-warning mt-3">
                        <h5 class="alert-heading">
                            <i class="ti ti-alert-triangle me-2"></i>Các Dòng Bị Lỗi
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Dòng</th>
                                        <th>Thuộc tính</th>
                                        <th>Lỗi</th>
                                        <th>Giá trị</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(session('import_failures') as $failure)
                                        @foreach($failure->errors() as $error)
                                            <tr>
                                                <td>{{ $failure->row() }}</td>
                                                <td>{{ $failure->attribute() }}</td>
                                                <td class="text-danger">{{ $error }}</td>
                                                <td>{{ $failure->values()[$failure->attribute()] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="block block-rounded p-3">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <i class="ti ti-user-plus me-2"></i>Thông Tin Võ Sinh
                </h3>
            </div>
            <div class="block-content block-content-full">
                <form action="{{ route($adminPrefix . '.' . $userPrefix . '.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    {{-- Thông tin cơ bản --}}
                    <div class="mb-4 pb-3 border-bottom">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="ho_va_ten">
                                    Họ và Tên <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('ho_va_ten') is-invalid @enderror" 
                                    id="ho_va_ten" name="ho_va_ten" 
                                    value="{{ old('ho_va_ten') }}" 
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
                                    value="{{ old('ngay_thang_nam_sinh') }}" required>
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
                                    <option value="Nam" {{ old('gioi_tinh') == 'Nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="Nữ" {{ old('gioi_tinh') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
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
                                    value="{{ old('ma_hoi_vien') }}" 
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
                                    value="{{ old('email') }}" 
                                    placeholder="example@email.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="phone">Số Điện Thoại</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                    id="phone" name="phone" 
                                    value="{{ old('phone') }}" 
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
                                        placeholder="Nhập địa chỉ đầy đủ">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Thông tin liên hệ khẩn cấp --}}
                    <div class="mb-4 pb-3 border-bottom">
                        <h5 class="mb-3 text-primary">
                            <i class="ti ti-alert-circle me-2"></i>Liên Hệ Khẩn Cấp
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="emergency_contact_name">Tên Người Liên Hệ</label>
                                <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                    id="emergency_contact_name" name="emergency_contact_name" 
                                    value="{{ old('emergency_contact_name') }}" 
                                    placeholder="Tên người liên hệ khẩn cấp">
                                @error('emergency_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="emergency_contact_phone">SĐT Người Liên Hệ</label>
                                <input type="text" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                                    id="emergency_contact_phone" name="emergency_contact_phone" 
                                    value="{{ old('emergency_contact_phone') }}" 
                                    placeholder="0123456789">
                                @error('emergency_contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Thông tin CLB và cấp đai --}}
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
                                    value="{{ old('ma_clb') }}" 
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
                                    value="{{ old('ma_don_vi') }}" 
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
                                    value="{{ old('quyen_so') }}" 
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
                                        <option value="{{ $capDaiItem->id }}" {{ old('cap_dai_id') == $capDaiItem->id ? 'selected' : '' }}>
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
                                        <option value="{{ $key ? 1 : 0 }}" {{ old('active_status', 1) == ($key ? 1 : 0) ? 'selected' : '' }}>
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

                    {{-- Nút submit --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route($adminPrefix . '.' . $userPrefix . '.index') }}" class="btn btn-alt-secondary">
                                    <i class="ti ti-x me-1"></i>Hủy
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-check me-1"></i>Thêm Mới
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
@endsection
@push('js_after')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const importFileBtn = document.getElementById('importFileBtn');
        const importFileInput = document.getElementById('importFileInput');
        const importForm = document.getElementById('importForm');
        
        // Debug: Kiểm tra các element có tồn tại không
        console.log('importFileBtn:', importFileBtn);
        console.log('importFileInput:', importFileInput);
        console.log('importForm:', importForm);
        
        // Khi click vào link, trigger click vào input file
        if (importFileBtn && importFileInput) {
            importFileBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Button clicked, triggering file input...');
                importFileInput.click();
            });
        } else {
            console.error('Missing elements: importFileBtn or importFileInput');
        }
        
        // Khi chọn file, tự động submit
        if (importFileInput) {
            importFileInput.addEventListener('change', function() {
                console.log('File selected:', importFileInput.files);
                if (importFileInput.files.length > 0) {
                    importForm.submit();
                    // Hiển thị loading
                    if (importFileBtn) {
                        importFileBtn.innerHTML = '<i class="ti ti-loader me-1"></i>Đang import...';
                        importFileBtn.style.pointerEvents = 'none';
                    }
                }
            });
        }
    });
</script>
@endpush