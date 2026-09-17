@php
use App\Helpers\BaseHelper;
use Illuminate\Support\Str;
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
                        Danh Sách Chi Nhánh
                    </h1>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route($adminPrefix . '.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Chi Nhánh
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="block block-rounded p-3">
            <div class="block-header block-header-default">
                <h3 class="block-title">Danh Sách Chi Nhánh</h3>
                <div class="block-options">
                    <a href="{{ route($adminPrefix . '.' . $chiNhanhPrefix . '.create') }}" class="btn btn-sm btn-primary">
                        <i class="ti ti-plus me-1"></i> Thêm Chi Nhánh
                    </a>
                </div>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">STT</th>
                                <th>Mã Chi Nhánh</th>
                                <th>Tên Chi Nhánh</th>
                                <th>Câu Lạc Bộ</th>
                                <th>Địa Chỉ</th>
                                <th>Điện Thoại</th>
                                <th>Email</th>
                                <th class="text-center" style="width: 100px;">Trạng Thái</th>
                                <th class="text-center" style="width: 150px;">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($chiNhanh ?? [] as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $item->branch_code }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $item->name }}</strong>
                                </td>
                                <td>
                                    {{ $item->club->name ?? '-' }}
                                </td>
                                <td>
                                    {{ $item->address ?? '-' }}
                                </td>
                                <td>
                                    {{ $item->phone ?? '-' }}
                                </td>
                                <td>
                                    {{ $item->email ?? '-' }}
                                </td>
                                <td class="text-center">
                                    @if($item->is_active)
                                        <span class="badge bg-success">Hoạt động</span>
                                    @else
                                        <span class="badge bg-secondary">Ngừng hoạt động</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route($adminPrefix . '.' . $chiNhanhPrefix . '.edit', $item->id) }}"
                                            class="btn btn-sm btn-alt-primary"
                                            data-bs-toggle="tooltip"
                                            title="Chỉnh sửa">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route($adminPrefix . '.' . $chiNhanhPrefix . '.destroy', $item->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa chi nhánh này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-alt-danger"
                                                data-bs-toggle="tooltip"
                                                title="Xóa">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">
                                    <div class="alert alert-info mb-0">
                                        <i class="ti ti-info-circle me-2"></i>Không có dữ liệu.
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if(isset($chiNhanh) && $chiNhanh->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $chiNhanh->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

