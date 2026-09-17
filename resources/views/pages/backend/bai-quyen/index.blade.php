@php
use App\Helpers\BaseHelper;
use Illuminate\Support\Str;
$adminPrefix = BaseHelper::getAdminPrefix();
$baiQuyenPrefix = config('core.routes.bai_quyen.prefix');
@endphp
@extends('layout.backend.backend')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-2">
                        Danh Sách Bài Quyền
                    </h1>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route($adminPrefix . '.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Bài Quyền
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="block block-rounded p-3">
            
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">STT</th>
                                <th>Tên Bài Quyền (Tiếng Việt)</th>
                                 <th>Tên Bài Quyền (English)</th> 
                                <th>Tên Bài Quyền (Korean)</th>
                                <th>Cấp Độ</th>
                                <th class="text-center">Số Động Tác</th>
                                <th class="text-center">Thời Gian (giây)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($baiQuyen ?? [] as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $item->ten_bai_quyen_vietnamese }}</strong>
                                    @if($item->mo_ta)
                                        <br><small class="text-muted">{{ Str::limit($item->mo_ta, 50) }}</small>
                                    @endif
                                </td>
                                <td>{{ $item->ten_bai_quyen_english }}</td> 
                                <td>{{ $item->ten_bai_quyen_korean ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $item->cap_do }}</span>
                                </td>
                                <td class="text-center">{{ $item->so_dong_tac ?? '-' }}</td>
                                <td class="text-center">{{ $item->thoi_gian_thuc_hien ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="alert alert-info mb-0">
                                        <i class="ti ti-info-circle me-2"></i>Không có dữ liệu. Vui lòng chạy seeder để thêm dữ liệu mẫu.
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