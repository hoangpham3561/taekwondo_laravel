@php
use App\Helpers\BaseHelper;
use Illuminate\Support\Str;
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
                        Danh Sách Khoá Học 
                    </h1>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route($adminPrefix . '.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Khoá Học 
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="block block-rounded p-3">
            <div class="block-header block-header-default">
                
                <div class="block-options">
                    <a href="{{ route($adminPrefix . '.' . $khoaHocPrefix . '.create') }}" class="btn btn-sm btn-primary">
                        <i class="ti ti-plus me-1"></i>Thêm Mới
                    </a>
                </div>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">STT</th>
                            <th>Tên Khóa Học</th>
                            <th>HLV</th>
                            <th>Câu Lạc Bộ</th>
                            <th>Cấp Độ</th>
                            <th>Quarter</th>
                            <th>Năm</th>
                            <th>Ngày Bắt Đầu</th>
                            <th>Ngày Kết Thúc</th>
                            <th>Số HV</th>
                            <th>Trạng Thái</th>
                            <th class="text-center" style="width: 150px;">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($khoaHoc ?? [] as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $item->title }}</strong>
                                @if($item->description)
                                    <br><small class="text-muted">{{ Str::limit($item->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $item->coach->ho_va_ten ?? '-' }}</span>
                            </td>
                            <td>{{ $item->club->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $item->level }}</span>
                            </td>
                            <td class="text-center">{{ $item->quarter }}</td>
                            <td class="text-center">{{ $item->year }}</td>
                            <td>
                                {{ $item->start_date ? $item->start_date->format('d/m/Y') : '-' }}
                            </td>
                            <td>
                                {{ $item->end_date ? $item->end_date->format('d/m/Y') : '-' }}
                            </td>
                            <td class="text-center">{{ $item->current_students ?? 0 }}</td>
                            <td>
                                @if($item->is_active)
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary">Ngừng hoạt động</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route($adminPrefix . '.' . $khoaHocPrefix . '.show', $item->id) }}" 
                                    class="btn btn-sm btn-alt-info" 
                                    title="Chi tiết">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    <a href="{{ route($adminPrefix . '.' . $khoaHocPrefix . '.edit', $item->id) }}" 
                                    class="btn btn-sm btn-alt-primary" 
                                    title="Chỉnh sửa">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <form action="{{ route($adminPrefix . '.' . $khoaHocPrefix . '.destroy', $item->id) }}" 
                                        method="POST" class="d-inline"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-alt-danger" title="Xóa">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center">
                                <div class="alert alert-info mb-0">
                                    <i class="ti ti-info-circle me-2"></i>Không có dữ liệu khóa học. 
                                    
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

{{-- Pagination --}}
{{ $khoaHoc->links() }}

                </div>
            </div>
        </div>
    </div>
@endsection

