@php
use App\Helpers\BaseHelper;
$adminPrefix = BaseHelper::getAdminPrefix();
$chiNhanhPrefix = config('core.routes.chi_nhanh.prefix');
@endphp
@extends('layout.backend.backend')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-2">
                        Chỉnh Sửa Chi Nhánh
                    </h1>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route($adminPrefix . '.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route($adminPrefix . '.' . $chiNhanhPrefix . '.index') }}">Chi Nhánh</a>
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
                <h3 class="block-title">Thông Tin Chi Nhánh</h3>
            </div>
            <div class="block-content block-content-full">
                <form action="{{ route($adminPrefix . '.' . $chiNhanhPrefix . '.update', $chiNhanh->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="club_id">
                                Câu Lạc Bộ <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('club_id') is-invalid @enderror" 
                                id="club_id" name="club_id" required>
                                <option value="">-- Chọn Câu Lạc Bộ --</option>
                                @foreach($clubs as $id => $name)
                                    <option value="{{ $id }}" {{ old('club_id', $chiNhanh->club_id) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('club_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="branch_code">
                                Mã Chi Nhánh <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('branch_code') is-invalid @enderror" 
                                id="branch_code" name="branch_code" 
                                value="{{ old('branch_code', $chiNhanh->branch_code) }}" 
                                placeholder="VD: GXTN, THTN" required>
                            @error('branch_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="name">
                                Tên Chi Nhánh <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" 
                                value="{{ old('name', $chiNhanh->name) }}" 
                                placeholder="Nhập tên chi nhánh" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="address">
                                Địa Chỉ
                            </label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                id="address" name="address" 
                                value="{{ old('address', $chiNhanh->address) }}" 
                                placeholder="Nhập địa chỉ">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="phone">
                                Điện Thoại
                            </label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                id="phone" name="phone" 
                                value="{{ old('phone', $chiNhanh->phone) }}" 
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
                                value="{{ old('email', $chiNhanh->email) }}" 
                                placeholder="Nhập email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="is_active">
                                Trạng Thái
                            </label>
                            <select class="form-select @error('is_active') is-invalid @enderror" 
                                id="is_active" name="is_active">
                                <option value="1" {{ old('is_active', $chiNhanh->is_active) == 1 ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ old('is_active', $chiNhanh->is_active) == 0 ? 'selected' : '' }}>Ngừng hoạt động</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check me-1"></i> Cập Nhật
                        </button>
                        <a href="{{ route($adminPrefix . '.' . $chiNhanhPrefix . '.index') }}" class="btn btn-alt-secondary">
                            <i class="ti ti-x me-1"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

