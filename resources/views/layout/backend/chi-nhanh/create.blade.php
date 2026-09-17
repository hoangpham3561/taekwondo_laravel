@php
use App\Helpers\BaseHelper;
$adminPrefix = BaseHelper::getAdminPrefix();
$chiNhanhPrefix = config('core.routes.chi_nhanh.prefix', 'chi-nhanh');
@endphp

@extends('layout.system.backend')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title mb-1">Thêm chi nhánh</h3>
            <ul class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route($adminPrefix . '.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route($adminPrefix . '.' . $chiNhanhPrefix . '.index') }}">Chi Nhánh</a></li>
                <li class="breadcrumb-item active">Thêm mới</li>
            </ul>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route($adminPrefix . '.' . $chiNhanhPrefix . '.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label">Câu lạc bộ</label>
                    <select name="club_id" class="form-select @error('club_id') is-invalid @enderror" required>
                        <option value="">-- Chọn CLB --</option>
                        @foreach(($clubs ?? []) as $id => $name)
                            <option value="{{ $id }}" {{ (string)old('club_id') === (string)$id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('club_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">Mã chi nhánh</label>
                    <input
                        type="text"
                        name="branch_code"
                        value="{{ old('branch_code') }}"
                        class="form-control @error('branch_code') is-invalid @enderror"
                        maxlength="20"
                        required
                    >
                    @error('branch_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Tên chi nhánh</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror"
                        maxlength="100"
                        required
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Địa chỉ</label>
                    <input
                        type="text"
                        name="address"
                        value="{{ old('address') }}"
                        class="form-control @error('address') is-invalid @enderror"
                        maxlength="255"
                    >
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">Số điện thoại</label>
                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        class="form-control @error('phone') is-invalid @enderror"
                        maxlength="20"
                    >
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror"
                        maxlength="100"
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="is_active"
                            value="1"
                            id="is_active"
                            {{ old('is_active', 1) ? 'checked' : '' }}
                        >
                        <label class="form-check-label" for="is_active">Đang hoạt động</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Tạo mới</button>
                <a href="{{ route($adminPrefix . '.' . $chiNhanhPrefix . '.index') }}" class="btn btn-outline-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection
