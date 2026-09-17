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
            <h3 class="page-title mb-1">Chi Nhánh</h3>
            <ul class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route($adminPrefix . '.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Chi Nhánh</li>
            </ul>
        </div>
        <div class="col-auto">
            <a href="{{ route($adminPrefix . '.' . $chiNhanhPrefix . '.create') }}" class="btn btn-primary">
                Thêm chi nhánh
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-12 col-md-4">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control"
                    placeholder="Tìm theo tên, mã, địa chỉ..."
                >
            </div>
            <div class="col-12 col-md-3">
                <select name="club_id" class="form-select">
                    <option value="">-- Tất cả CLB --</option>
                    @foreach(($clubs ?? []) as $id => $name)
                        <option value="{{ $id }}" {{ (string)request('club_id') === (string)$id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select name="is_active" class="form-select">
                    <option value="">-- Trạng thái --</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Tạm ngưng</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-grid d-md-flex gap-2">
                <button type="submit" class="btn btn-outline-primary">Lọc</button>
                <a href="{{ route($adminPrefix . '.' . $chiNhanhPrefix . '.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 80px;">#</th>
                        <th>Mã</th>
                        <th>Tên chi nhánh</th>
                        <th>CLB</th>
                        <th>Địa chỉ</th>
                        <th>Điện thoại</th>
                        <th>Email</th>
                        <th class="text-center" style="width: 140px;">Trạng thái</th>
                        <th class="text-end" style="width: 150px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($chiNhanh ?? []) as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->branch_code }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->club->name ?? '-' }}</td>
                            <td>{{ $item->address }}</td>
                            <td>{{ $item->phone }}</td>
                            <td>{{ $item->email }}</td>
                            <td class="text-center">
                                @if((int)($item->is_active ?? 0) === 1)
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary">Tạm ngưng</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group" aria-label="Actions">
                                    <a
                                        href="{{ route($adminPrefix . '.' . $chiNhanhPrefix . '.edit', $item->id) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Sửa
                                    </a>
                                    <form
                                        action="{{ route($adminPrefix . '.' . $chiNhanhPrefix . '.destroy', $item->id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Bạn chắc chắn muốn xóa chi nhánh này?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Chưa có dữ liệu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(($chiNhanh ?? null) && method_exists($chiNhanh, 'links'))
            <div class="row align-items-center mt-3">
                {{ $chiNhanh->appends(request()->input())->links('layout.system.partials.pagination') }}
            </div>
        @endif
    </div>
</div>
@endsection
