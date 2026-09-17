@php
use App\Helpers\BaseHelper;
$adminPrefix = BaseHelper::getAdminPrefix();
$userPrefix = config('core.routes.user.prefix');
@endphp
@extends('layout.system.backend')
@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-2">
                    User Edit
                </h1>
            </div>
            <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="javascript:void(0)">App</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        User
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Edit
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="content">
    @include('layout.system.partials.message')
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Add User</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12 space-y-5">
                    <form action="{{ route($adminPrefix . '.' . $userPrefix . '.update', ['user' => $data->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="user_id" value="{{ $data->id }}">
                        <div class="row push">
                            <div class="col-lg-4">

                            </div>
                            <div class="col-lg-8 col-xl-5">
                                <div class="mb-4">
                                    <label class="form-label" for="username">UserName</label>
                                    <input type="text" 
                                        class="form-control @error('username') is-invalid @enderror" 
                                        id="username" 
                                        name="username" 
                                        value="{{ old('username', $data['UserName']) }}" 
                                        placeholder="Nhập username">
                                    @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-draft">Username phải là duy nhất trong hệ thống</small>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="example-text-input">Password</label>
                                    <input type="text" class="form-control" id="example-text-input" name="Pass" type="password" value="{{ $data['Pass'] }}">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="example-email-input">Email</label>
                                    <input type="email" class="form-control" id="example-email-input" name="email" value="{{ $data['Email'] }}" placeholder="Please enter value email">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="example-text-input">FullName</label>
                                    <input type="text" class="form-control" id="example-text-input" name="name" value="{{ $data['FullName'] }}" placeholder="Please enter value name">
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="muc_id">Set đơn hàng</label>
                                    <select class="form-select" id="muc_id" name="muc_id">
                                        <option value="0" {{ (isset($currentMucID) && $currentMucID == 0) ? 'selected' : '' }}>Khách hàng</option>
                                        <option value="1" {{ (isset($currentMucID) && $currentMucID == 1) ? 'selected' : '' }}>Chuyên nghiệp</option>
                                        <option value="2" {{ (isset($currentMucID) && $currentMucID == 2) ? 'selected' : '' }}>Gói Đồng</option>
                                        <option value="3" {{ (isset($currentMucID) && $currentMucID == 3) ? 'selected' : '' }}>Gói Bạc</option>
                                        <option value="4" {{ (isset($currentMucID) && $currentMucID == 4) ? 'selected' : '' }}>Gói Vàng</option>
                                        <option value="5" {{ (isset($currentMucID) && $currentMucID == 5) ? 'selected' : '' }}>Gói Kim Cương</option>
                                    </select>
                                    <small class="text-muted">Chọn cấp bậc đơn hàng cho user</small>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="level_title_id">Set danh hiệu</label>
                                    <select class="form-select" id="level_title_id" name="level_title_id">
                                        <option value="0" {{ (isset($currentLevelID) && $currentLevelID == 0) ? 'selected' : '' }}>Không có</option>
                                        <option value="1" {{ (isset($currentLevelID) && $currentLevelID == 1) ? 'selected' : '' }}>Giám Sát Kinh Doanh</option>
                                        <option value="2" {{ (isset($currentLevelID) && $currentLevelID == 2) ? 'selected' : '' }}>Quản Lý Kinh Doanh</option>
                                        <option value="3" {{ (isset($currentLevelID) && $currentLevelID == 3) ? 'selected' : '' }}>Phó Giám Đốc Kinh Doanh</option>
                                    </select>
                                    <small class="text-muted">Chọn danh hiệu cho user</small>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="rank_id">Set cửa hàng</label>
                                    <select class="form-select" id="rank_id" name="rank_id">
                                        <option value="0" {{ (isset($currentRankID) && $currentRankID == 0) ? 'selected' : '' }}>Không</option>
                                        <option value="1" {{ (isset($currentRankID) && $currentRankID == 1) ? 'selected' : '' }}>Có</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection