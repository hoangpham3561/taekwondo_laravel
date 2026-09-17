@php
use App\Helpers\BaseHelper;
use Illuminate\Support\Str;
$adminPrefix = BaseHelper::getAdminPrefix();
$huanLuyenVienPrefix = config('core.routes.huan_luyen_vien.prefix');
@endphp
@extends('layout.backend.backend')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-2">
                        Danh Sách Huấn Luyện Viên
                    </h1>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route($adminPrefix . '.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Huấn Luyện Viên
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
                    <a href="{{ route($adminPrefix . '.' . $huanLuyenVienPrefix . '.create') }}" class="btn btn-sm btn-primary">
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
                                <th>Mã Hội Viên</th>
                                <th>Họ và Tên</th>
                                <th>Ngày Sinh</th>
                                <th>Giới Tính</th>
                                <th>Email</th>
                                <th>Điện Thoại</th>
                                <th>Cấp Đai</th>
                                <th>Vai Trò</th>
                                <th>Trạng Thái</th>
                                <th class="text-center" style="width: 150px;">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($huanLuyenVien ?? [] as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <code class="text-primary">{{ $item->ma_hoi_vien ?? '-' }}</code>
                                </td>
                                <td>
                                    <strong>{{ $item->ho_va_ten ?? '-' }}</strong>
                                </td>
                                <td>
                                    {{ $item->ngay_thang_nam_sinh ? \Carbon\Carbon::parse($item->ngay_thang_nam_sinh)->format('d/m/Y') : '-' }}
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $item->gioi_tinh ?? '-' }}</span>
                                </td>
                                <td>{{ $item->email ?? '-' }}</td>
                                <td>{{ $item->phone ?? '-' }}</td>
                                <td>
                                    @if(isset($item->capDai))
                                        <span class="badge bg-success">{{ $item->capDai->name ?? '-' }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->role === 'owner')
                                        <span class="badge bg-danger">Owner</span>
                                    @else
                                        <span class="badge bg-warning">Admin</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->is_active ?? true)
                                        <span class="badge bg-success">Hoạt động</span>
                                    @else
                                        <span class="badge bg-secondary">Không hoạt động</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route($adminPrefix . '.' . $huanLuyenVienPrefix . '.edit', $item->id) }}" 
                                           class="btn btn-sm btn-alt-primary" 
                                           data-bs-toggle="tooltip" 
                                           title="Chỉnh sửa">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route($adminPrefix . '.' . $huanLuyenVienPrefix . '.destroy', $item->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
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
                                <td colspan="11" class="text-center">
                                    <div class="alert alert-info mb-0">
                                        <i class="ti ti-info-circle me-2"></i>Không có dữ liệu. 
                                        @if(isset($huanLuyenVien))
                                            Vui lòng thêm huấn luyện viên mới.
                                        @else
                                            Vui lòng cập nhật controller để lấy dữ liệu từ database.
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

