@php
use App\Helpers\BaseHelper;
use App\Enums\UserStatusEnum;

$statusGroup = $users->countBy('status');
$adminPrefix = BaseHelper::getAdminPrefix();
$userPrefix = config('core.routes.user.prefix');
@endphp
@extends('layout.system.backend')
@section('css_after')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection
@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-2">
                    User
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
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="content">
    @include('layout.system.partials.message')
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">List</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option">
                    <i class="mdi mdi-cog"></i>
                </button>
            </div>
        </div>
        <div class="block-content">
            <!-- Form tìm kiếm -->
            <div class="row mb-4">
                <div class="col-12">
                    <form method="GET" action="{{ route($adminPrefix . '.' . $userPrefix . (isset($isInactivePage) && $isInactivePage ? '.inactive' : '.index')) }}" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tìm kiếm (UserName / Email / FullName)</label>
                            <input type="text"
                                class="form-control"
                                name="username"
                                value="{{ request('username') }}"
                                placeholder="Nhập UserName, Email hoặc FullName...">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Đơn hàng</label>
                            <select class="form-select" name="muc_id">
                                @foreach($mucNames as $key => $name)
                                <option value="{{ $key }}" {{ request('muc_id') === (string)$key ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Danh hiệu</label>
                            <select class="form-select" name="level_id">
                                @foreach($levelNames as $key => $name)
                                <option value="{{ $key }}" {{ request('level_id') === (string)$key ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fa fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                        <!-- Giữ lại các filter khác nếu có -->
                        @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8">
                    <div class="table-responsive">
                        @if(!empty($statusGroup))
                        <div class="dt-buttons mb-3">
                            <a href="{{ route($adminPrefix . '.' . $userPrefix . (isset($isInactivePage) && $isInactivePage ? '.inactive' : '.index')) }}" class="dt-button buttons-copy buttons-html5 btn btn-sm btn-alt-primary" tabindex="0" aria-controls="DataTables_Table_3" type="button">
                                <span>Tất cả</span>
                                <span class="nav-main-link-badge badge rounded-pill bg-primary">{{ $users->total() }}</span>
                            </a>
                            @foreach($groupByStatus as $key => $value)
                            @php
                            $btnClass = '';
                            $bgClass = '';
                            if ($key === UserStatusEnum::ACTIVE) {
                            $btnClass = 'btn-alt-info';
                            $bgClass = 'bg-info';
                            }

                            if ($key === UserStatusEnum::INACTIVE) {
                            $btnClass = 'btn-alt-success';
                            $bgClass = 'bg-success';
                            }

                            @endphp
                            <a href="{{ route($adminPrefix . '.' . $userPrefix . (isset($isInactivePage) && $isInactivePage ? '.inactive' : '.index')) }}?status={{ $key }}" class="dt-button buttons-csv buttons-html5 btn btn-sm {{ $btnClass }}" tabindex="0" aria-controls="DataTables_Table_3" type="button">
                                <span>{{ ucfirst($key) }}</span>
                                <span class="nav-main-link-badge badge rounded-pill {{ $bgClass }}">{{ $value }}</span>
                            </a>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                {{-- <div class="col-sm-4">--}}
                {{-- <a href="{{ route($adminPrefix . '.' . $userPrefix . '.create') }}" class="btn btn-sm btn-outline-primary float-end">Add New</a>--}}
                {{-- </div>--}}
            </div>
            <div class="table-scrollable-wrapper">
                <table class="table table-bordered table-striped table-vcenter table-scrollable">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 20px;">
                                No.
                            </th>
                            <th style="width: 120px;">Người giới thiệu</th>
                            <th style="width: 120px;">Full Name</th>
                            <th style="width: 200px;">Email</th>
                            <th style="width: 120px;">UserName</th>
                            <th style="width: 120px;">Password</th>
                            <th style="width: 102px;">Trạng thái</th>
                            <th style="width: 102px;">Đơn hàng</th>
                            <th style="width: 102px;">Danh hiệu</th>
                            <th style="width: 102px;">CCCD/CMND</th>
                            <th style="width: 102px;">SĐT</th>
                            <th style="width: 102px;">Ngày sinh</th>
                            <th style="width: 102px;">Địa chỉ</th>
                            <th style="width: 102px;">Tên ngân hàng</th>
                            <th style="width: 102px;">Số tài khoản</th>
                            <th style="width: 102px;">Tên tài khoản</th>

                            <th style="width: 180px">Ngày đăng ký</th>
                            @if(!isset($isInactivePage) || !$isInactivePage)
                            <th class="text-center" style="width: 150px;">Công cụ</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($users))
                        @foreach($users as $item)
                        <tr>
                            <td class="text-center">
                                {{ $item->UserID }}
                            </td>
                            <td class="text-center">
                                @if($item->f1Referrer)
                                {{$item->f1Referrer->UserName }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="fw-semibold fs-sm">
                                <a>{{ $item->FullName }}</a>
                            </td>
                            <td class="fs-sm">{{ $item->Email }}</td>
                            <td class="fs-sm">{{ $item->UserName }}</td>
                            <td class="fs-sm">{{ $item->Pass }}</td>
                            <td>
                                @php
                                $class = '';
                                if ($item->Active === 'Y') {
                                $class = 'bg-info-light text-info';
                                }
                                if ($item->Active === 'N') {
                                $class = 'bg-warning-light text-warning';
                                }
                                @endphp
                                <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill {{ $class }}">{{ $item->Active }}</span>
                            </td>
                            
                            @php
                                $mucId = $item->node->MucID ?? 0;
                                $levelId = $item->node->LevelID ?? 0;
                                $mucName = $mucNames[$mucId] ?? 'N/A';

                                $levelName = $levelNames[$levelId] ?? 'N/A';
                                if($levelId == 0 && $mucId >= 1) {
                                    $levelName = 'Đại lý chuyên nghiệp';
                                } 
                            @endphp
                            <td>{{ $mucName }}</td>
                            <td>{{ $levelName }}</td>

                            <td>{{ $item->CMND }}</td>
                            <td>{{ $item->Phone }}</td>
                            <td>{{ $item->Birthday }}</td>
                            <td>{{ $item->Address }}</td>
                            <td>{{ $item->NganHang }}</td>
                            <td>{{ $item->STK }}</td>
                            <td>{{ $item->Ten_TK }}</td>

                            <td>{{ $item->DateReg }}</td>
                            @if(!isset($isInactivePage) || !$isInactivePage)
                            <td class="text-center">
                                <div class="btn-group">
                                    @php
                                        $userId = $item->id ?? $item->UserID ?? null;
                                    @endphp
                                    @if($userId)
                                        <a href="{{ route($adminPrefix . '.' . $userPrefix . '.edit', ['user' => $userId]) }}" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" style="margin-right: 10px;" data-bs-toggle="tooltip" title="" data-bs-original-title="Edit">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            {{ $users->appends(request()->input())->links('layout.system.partials.pagination') }}
        </div>
    </div>
</div>
@endsection
@section('js_after')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
@endsection