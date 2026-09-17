@php
use App\Helpers\BaseHelper;
use Illuminate\Support\Str;
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
                        Danh Sách Câu Lạc Bộ
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded p-3">
            <div class="block-header block-header-default">
                <div class="block-options">
                    <a href="{{ route($adminPrefix . '.' . $cauLacBoPrefix . '.create') }}" class="btn btn-sm btn-primary">
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
                                <th>Mã Câu Lạc Bộ</th>
                                <th>Tên Câu Lạc Bộ</th>
                                <th>Địa Chỉ</th>
                                <th>Điện Thoại</th>
                                <th>Email</th>
                                <th>Huấn Luyện Viên</th>  <!-- ✅ Thêm HLV -->
                                <th class="text-center" style="width: 150px;">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cauLacBo ?? [] as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>  <!-- ✅ STT -->
                                <td>
                                    <code class="text-primary">{{ $item->club_code ?? '-' }}</code>
                                </td>
                                <td>
                                    <strong>{{ $item->name ?? '-' }}</strong>
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
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $item->head_coach_name ?? 'Chưa có HLV' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route($adminPrefix . '.' . $cauLacBoPrefix . '.edit', $item->id) }}" 
                                        class="btn btn-sm btn-alt-primary" 
                                        data-bs-toggle="tooltip" 
                                        title="Chỉnh sửa">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route($adminPrefix . '.' . $cauLacBoPrefix . '.destroy', $item->id) }}" 
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
                                <td colspan="8" class="text-center">  <!-- ✅ Đổi từ 11 → 8 -->
                                    <div class="alert alert-info mb-0">
                                        <i class="ti ti-info-circle me-2"></i>Không có dữ liệu. 
                                        Vui lòng thêm câu lạc bộ mới.
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