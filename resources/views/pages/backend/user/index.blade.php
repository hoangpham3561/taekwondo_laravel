@php
use App\Helpers\BaseHelper;

$statusGroup = $users->countBy('active_status');
$adminPrefix = BaseHelper::getAdminPrefix();
$userPrefix = config('core.routes.user.prefix');
@endphp
@extends('layout.backend.backend')
@section('css_after')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection
@section('content')
<div class="content user-list-page">
    @include('layout.backend.partials.message')
    <div class="block block-rounded p-3">
        <div class="block-header block-header-default">
            <h3 class="block-title">Danh Sách Võ Sinh</h3>
            <div class="block-options">
                <a href="{{ route($adminPrefix . '.' . $userPrefix . '.import') }}" class="btn btn-sm btn-alt-primary me-2">
                    <i class="ti ti-file-upload me-1"></i>Import Excel/CSV
                </a>
                <a href="{{ route($adminPrefix . '.' . $userPrefix . '.create') }}" class="btn btn-sm btn-primary">
                    <i class="ti ti-plus me-1"></i>Thêm Mới
                </a>
            </div>
        </div>
        <div class="block-content">
            <!-- Form tìm kiếm -->
            <div class="row mb-4">
                <div class="col-12">
                    <form method="GET" action="{{ route($adminPrefix . '.' . $userPrefix . (isset($isInactivePage) && $isInactivePage ? '.inactive' : '.index')) }}" class="user-filter-bar">
                        <div class="user-filter-item">
                            <label class="form-label">Tìm kiếm (Mã hội viên / Email / Họ tên)</label>
                            <input type="text"
                                class="form-control"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Nhập mã hội viên, email hoặc họ tên...">
                        </div>

                        <div class="user-filter-item">
                            <label class="form-label">Trạng thái</label>
                            <select class="form-select" name="status">
                                <option value="">Tất cả</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                        </div>

                        <div class="user-filter-item">
                            <label class="form-label">Cấp Đai</label>
                            <select class="form-select" name="cap_dai_id">
                                <option value="">Tất cả</option>
                                @foreach($capDai ?? [] as $capDaiItem)
                                    <option value="{{ $capDaiItem->id }}" {{ request('cap_dai_id') == $capDaiItem->id ? 'selected' : '' }}>
                                        {{ $capDaiItem->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="user-filter-actions">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fa fa-search"></i> Tìm kiếm
                            </button>
                            <a href="{{ route($adminPrefix . '.' . $userPrefix . (isset($isInactivePage) && $isInactivePage ? '.inactive' : '.index')) }}" class="btn btn-alt-secondary">
                                <i class="fa fa-times"></i> Xóa bộ lọc
                            </a>
                        </div>
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
                            $label = '';
                            if ($key == 1 || $key === true) {
                            $btnClass = 'btn-alt-info';
                            $bgClass = 'bg-info';
                            $label = 'Hoạt động';
                            } else {
                            $btnClass = 'btn-alt-warning';
                            $bgClass = 'bg-warning';
                            $label = 'Không hoạt động';
                            }
                            @endphp
                            <a href="{{ route($adminPrefix . '.' . $userPrefix . (isset($isInactivePage) && $isInactivePage ? '.inactive' : '.index')) }}?status={{ $key }}" class="dt-button buttons-csv buttons-html5 btn btn-sm {{ $btnClass }}" tabindex="0" aria-controls="DataTables_Table_3" type="button">
                                <span>{{ $label }}</span>
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
                            <th style="width: 120px;">Mã hội viên</th>
                            <th style="width: 120px;">Họ và tên</th>
                            <th style="width: 200px;">Email</th>
                            <th style="width: 120px;">SĐT</th>
                            <th style="width: 120px;">Giới tính</th>
                            <th style="width: 120px;">Ngày sinh</th>
                            <th style="width: 120px;">Cấp đai</th>
                            <th style="width: 120px;">Mã CLB</th>
                            <th style="width: 102px;">Trạng thái</th>
                            <th style="width: 102px;">Địa chỉ</th>
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
                                {{ $item->id }}
                            </td>
                            <td class="fw-semibold fs-sm">
                                <a>{{ $item->ma_hoi_vien }}</a>
                            </td>
                            <td class="fw-semibold fs-sm">
                                <a>{{ $item->ho_va_ten }}</a>
                            </td>
                            <td class="fs-sm">{{ $item->email ?? '-' }}</td>
                            <td class="fs-sm">{{ $item->phone ?? '-' }}</td>
                            <td class="fs-sm">{{ $item->gioi_tinh ?? '-' }}</td>
                            <td class="fs-sm">{{ $item->ngay_thang_nam_sinh ? $item->ngay_thang_nam_sinh->format('d/m/Y') : '-' }}</td>
                            <td class="fs-sm">
                                @if($item->capDai)
                                    <span class="badge bg-info">{{ $item->capDai->name }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="fs-sm">{{ $item->ma_clb ?? '-' }}</td>
                            <td>
                                @php
                                $class = $item->active_status ? 'user-status-active' : 'user-status-inactive';
                                @endphp
                                <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill {{ $class }}">
                                    {{ $item->active_status ? 'Hoạt động' : 'Không hoạt động' }}
                                </span>
                            </td>
                            <td class="fs-sm">{{ $item->address ?? '-' }}</td>
                            <td class="fs-sm">{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</td>
                            @if(!isset($isInactivePage) || !$isInactivePage)
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route($adminPrefix . '.' . $userPrefix . '.edit', ['user' => $item->id]) }}" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" style="margin-right: 10px;" data-bs-toggle="tooltip" title="" data-bs-original-title="Edit">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
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
            {{ $users->appends(request()->input())->links('layout.backend.partials.pagination') }}
        </div>
    </div>
</div>
@endsection
@section('js_after')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
@endsection