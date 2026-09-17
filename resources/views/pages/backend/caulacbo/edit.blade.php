@php
use App\Helpers\BaseHelper;
$adminPrefix = BaseHelper::getAdminPrefix();
$cauLacBoPrefix = config('core.routes.cau_lac_bo.prefix');
@endphp
@extends('layout.backend.backend')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-2">
                        Chỉnh Sửa Câu Lạc Bộ
                    </h1>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route($adminPrefix . '.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route($adminPrefix . '.' . $cauLacBoPrefix . '.index') }}">Câu Lạc Bộ</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Chỉnh sửa
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
                    <i class="ti ti-building-community me-2"></i>Thông Tin Câu Lạc Bộ
                </h3>
            </div>
            <div class="block-content block-content-full">
                <form action="{{ route($adminPrefix . '.' . $cauLacBoPrefix . '.update', $cauLacBo->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-xl-8">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label" for="club_code">
                                        Mã Câu Lạc Bộ <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('club_code') is-invalid @enderror"
                                        id="club_code" name="club_code"
                                        value="{{ old('club_code', $cauLacBo->club_code) }}"
                                        placeholder="VD: CLB001" required maxlength="20">
                                    @error('club_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label" for="name">
                                        Tên Câu Lạc Bộ <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name"
                                        value="{{ old('name', $cauLacBo->name) }}"
                                        placeholder="Nhập tên câu lạc bộ" required maxlength="100">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="address">
                                    Địa Chỉ
                                </label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror"
                                    id="address" name="address"
                                    value="{{ old('address', $cauLacBo->address) }}"
                                    autocomplete="street-address">
                                <span class="form-text text-muted small">Tự điền theo Tỉnh/thành và Phường/xã bên dưới; có thể chỉnh thêm (số nhà, tên đường).</span>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="legacy_address">
                                    Địa chỉ hành chính cũ (trước sát nhập)
                                </label>
                                <textarea class="form-control @error('legacy_address') is-invalid @enderror"
                                    id="legacy_address" name="legacy_address" rows="2"
                                    placeholder="Ghi địa chỉ theo phường/xã — quận/huyện — tỉnh/thành cũ nếu cần lưu tham chiếu">{{ old('legacy_address', $cauLacBo->legacy_address) }}</textarea>
                                <span class="form-text text-muted small">Tùy chọn. Giữ bản ghi địa danh trước khi sáp nhập đơn vị hành chính (đối chiếu hồ sơ, bản đồ cũ).</span>
                                @error('legacy_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @include('pages.backend.caulacbo.partials.province-ward', [
                                'provinceCode' => old('province_code', $cauLacBo->province_code),
                                'wardCode' => old('ward_code', $cauLacBo->ward_code),
                            ])

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label" for="phone">
                                        Điện Thoại
                                    </label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone"
                                        value="{{ old('phone', $cauLacBo->phone) }}"
                                        placeholder="Số điện thoại" maxlength="20">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label" for="email">
                                        Email
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email"
                                        value="{{ old('email', $cauLacBo->email) }}"
                                        placeholder="email@example.com" maxlength="100">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="head_coach_id">
                                    Huấn Luyện Viên Trưởng
                                </label>
                                <select class="form-select @error('head_coach_id') is-invalid @enderror"
                                    id="head_coach_id" name="head_coach_id">
                                    <option value="">-- Chọn huấn luyện viên --</option>
                                    @foreach($coaches ?? [] as $id => $name)
                                        <option value="{{ $id }}" {{ (string) old('head_coach_id', $cauLacBo->head_coach_id ?? '') === (string) $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('head_coach_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="description">
                                    Mô Tả
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                    id="description" name="description" rows="4"
                                    placeholder="Mô tả ngắn về câu lạc bộ">{{ old('description', $cauLacBo->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="logo_file">
                                    Tải logo từ máy
                                </label>
                                <input type="file" class="form-control @error('logo_file') is-invalid @enderror"
                                    id="logo_file" name="logo_file"
                                    accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                                <span class="form-text text-muted small">JPEG, PNG, GIF, WebP — tối đa 2 MB. Chỉ thay logo khi chọn file mới.</span>
                                @error('logo_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if(old('logo_url', $cauLacBo->logo_url))
                                    <div class="mt-2">
                                        <span class="small text-muted d-block mb-1">Logo hiện tại</span>
                                        <img src="{{ old('logo_url', $cauLacBo->logo_url) }}" alt="" class="img-fluid rounded border" height="120" loading="lazy" onerror="this.style.display='none'">
                                    </div>
                                @endif
                            </div>
                            <div class="mb-0">
                                <label class="form-label" for="logo_url">
                                    Hoặc URL logo
                                </label>
                                <input type="url" class="form-control @error('logo_url') is-invalid @enderror"
                                    id="logo_url" name="logo_url"
                                    value="{{ old('logo_url', $cauLacBo->logo_url) }}"
                                    placeholder="https://…">
                                @error('logo_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="block block-rounded bg-body-light mb-0 p-3">
                                <div class="block-content py-3">
                                    <h5 class="text-muted fw-semibold mb-3 small text-uppercase">Tóm tắt</h5>
                                    <p class="mb-2 small text-muted">Mã bản ghi</p>
                                    <p class="fw-semibold mb-4">#{{ $cauLacBo->id }}</p>

                                    <div class="mb-4">
                                        <label class="form-label" for="images">
                                            Ảnh (JSON tùy chọn)
                                        </label>
                                        <textarea class="form-control @error('images') is-invalid @enderror"
                                            id="images" name="images" rows="3"
                                            placeholder='["url1","url2"]'>{{ old('images', $cauLacBo->images) }}</textarea>
                                        <span class="form-text text-muted small">Để trống nếu không dùng.</span>
                                        @error('images')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="border-top pt-3 mb-0">
                                        <a href="{{ route($adminPrefix . '.' . $cauLacBoPrefix . '.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                                            <i class="ti ti-list me-1"></i>Danh sách
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <hr class="my-0">
                            <div class="d-flex flex-wrap justify-content-end gap-2 pt-4">
                                <a href="{{ route($adminPrefix . '.' . $cauLacBoPrefix . '.index') }}" class="btn btn-alt-secondary">
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
