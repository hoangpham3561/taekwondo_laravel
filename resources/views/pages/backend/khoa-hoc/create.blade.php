@php
use App\Helpers\BaseHelper;
$adminPrefix = BaseHelper::getAdminPrefix();
$khoaHocPrefix = config('core.routes.khoa_hoc.prefix');
@endphp
@extends('layout.backend.backend')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-2">
                        Thêm Khóa Học Mới
                    </h1>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route($adminPrefix . '.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route($adminPrefix . '.' . $khoaHocPrefix . '.index') }}">Khóa Học</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Thêm Mới
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
                <h3 class="block-title">
                    <i class="ti ti-book me-2"></i>Thông Tin Khóa Học
                </h3>
            </div>
            <div class="block-content block-content-full">
                <form action="{{ route($adminPrefix . '.' . $khoaHocPrefix . '.store') }}" method="POST">
                    @csrf

                    <div class="row g-4">
                        <div class="col-xl-8">
                            <div class="mb-4">
                                <label class="form-label" for="title">
                                    Tên Khóa Học <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title"
                                    value="{{ old('title') }}"
                                    placeholder="Nhập tên khóa học" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label" for="level">
                                        Cấp Độ <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('level') is-invalid @enderror"
                                            id="level" name="level" required>
                                        <option value="">-- Chọn cấp độ --</option>
                                        <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>Cơ Bản</option>
                                        <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>Trung Cấp</option>
                                        <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>Nâng Cao</option>
                                    </select>
                                    @error('level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label" for="coach_id">
                                        Huấn Luyện Viên <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('coach_id') is-invalid @enderror"
                                            id="coach_id" name="coach_id" required>
                                        <option value="">-- Chọn huấn luyện viên --</option>
                                        @foreach($coaches ?? [] as $id => $name)
                                            <option value="{{ $id }}" {{ old('coach_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('coach_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="branch_id">
                                    Chi Nhánh <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('branch_id') is-invalid @enderror"
                                        id="branch_id" name="branch_id" required>
                                    <option value="">-- Chọn chi nhánh --</option>
                                    @foreach($branches ?? [] as $id => $name)
                                        <option value="{{ $id }}" {{ old('branch_id') == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="description">
                                    Mô Tả
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                        id="description" name="description"
                                        rows="4"
                                        placeholder="Nhập mô tả khóa học">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="club_id">
                                        Câu Lạc Bộ
                                    </label>
                                    <select class="form-select @error('club_id') is-invalid @enderror"
                                            id="club_id" name="club_id">
                                        <option value="">-- Chọn câu lạc bộ --</option>
                                        @foreach($clubs ?? [] as $id => $name)
                                            <option value="{{ $id }}" {{ old('club_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('club_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="quarter">
                                        Quý
                                    </label>
                                    <select class="form-select @error('quarter') is-invalid @enderror"
                                            id="quarter" name="quarter">
                                        <option value="">-- Chọn quý --</option>
                                        <option value="Q1" {{ old('quarter') == 'Q1' ? 'selected' : '' }}>Quý 1</option>
                                        <option value="Q2" {{ old('quarter') == 'Q2' ? 'selected' : '' }}>Quý 2</option>
                                        <option value="Q3" {{ old('quarter') == 'Q3' ? 'selected' : '' }}>Quý 3</option>
                                        <option value="Q4" {{ old('quarter') == 'Q4' ? 'selected' : '' }}>Quý 4</option>
                                    </select>
                                    @error('quarter')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="year">
                                        Năm
                                    </label>
                                    <input type="number" class="form-control @error('year') is-invalid @enderror"
                                        id="year" name="year"
                                        value="{{ old('year', date('Y')) }}"
                                        placeholder="VD: 2024" min="2000" max="2100">
                                    @error('year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="start_date">
                                        Ngày Bắt Đầu
                                    </label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                        id="start_date" name="start_date"
                                        value="{{ old('start_date') }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="end_date">
                                        Ngày Kết Thúc
                                    </label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                        id="end_date" name="end_date"
                                        value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="current_students">
                                        Số Học Viên Hiện Tại
                                    </label>
                                    <input type="number" class="form-control @error('current_students') is-invalid @enderror"
                                        id="current_students" name="current_students"
                                        value="{{ old('current_students', 0) }}"
                                        min="0" placeholder="0">
                                    @error('current_students')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="block block-rounded bg-body-light mb-0 p-3">
                                <div class="block-content py-3">
                                    <h5 class="text-muted fw-semibold mb-3 small text-uppercase">Xuất bản</h5>

                                    <p class="small text-muted mb-4">
                                        Điền các trường bắt buộc bên trái. Ảnh đại diện và trạng thái có thể chỉnh tại đây trước khi lưu.
                                    </p>

                                    <div class="mb-4">
                                        <label class="form-label" for="image_url">
                                            URL Hình Ảnh
                                        </label>
                                        <input type="url" class="form-control @error('image_url') is-invalid @enderror"
                                            id="image_url" name="image_url"
                                            value="{{ old('image_url') }}"
                                            placeholder="https://…">
                                        @error('image_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="mt-2" id="khoa-hoc-image-preview" aria-live="polite"></div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label" for="is_active">
                                            Trạng Thái
                                        </label>
                                        <select class="form-select @error('is_active') is-invalid @enderror"
                                                id="is_active" name="is_active">
                                            <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Hoạt Động</option>
                                            <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Không Hoạt Động</option>
                                        </select>
                                        @error('is_active')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="border-top pt-3 mb-0">
                                        <a href="{{ route($adminPrefix . '.' . $khoaHocPrefix . '.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                                            <i class="ti ti-list me-1"></i>Danh sách khóa học
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <hr class="my-0">
                            <div class="d-flex flex-wrap justify-content-end gap-2 pt-4">
                                <a href="{{ route($adminPrefix . '.' . $khoaHocPrefix . '.index') }}" class="btn btn-alt-secondary">
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
        (function () {
            var input = document.getElementById('image_url');
            var box = document.getElementById('khoa-hoc-image-preview');
            if (!input || !box) return;

            function setPreview(url) {
                box.innerHTML = '';
                if (!url) return;
                var img = document.createElement('img');
                img.src = url;
                img.alt = '';
                img.width = 280;
                img.loading = 'lazy';
                img.className = 'img-fluid rounded border';
                img.onerror = function () { box.innerHTML = ''; };
                box.appendChild(img);
            }

            function sync() {
                setPreview((input.value || '').trim());
            }

            input.addEventListener('input', sync);
            input.addEventListener('change', sync);
            sync();
        })();
    </script>
@endpush
