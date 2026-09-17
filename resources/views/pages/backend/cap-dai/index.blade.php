@php
use App\Helpers\BaseHelper;
use Illuminate\Support\Str;
$adminPrefix = BaseHelper::getAdminPrefix();
$capDaiPrefix = config('core.routes.cap_dai.prefix');
@endphp
@extends('layout.backend.backend')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-2">
                        Danh Sách Cấp Đai
                    </h1>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route($adminPrefix . '.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Cấp Đai
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
                                <th>Tên Cấp Đai</th>
                                <th>Màu Sắc</th>
                                <th>Bài Quyền Bắt Buộc</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($capDai ?? [] as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $item->name }}</strong>
                                    @if($item->description)
                                        <br><small class="text-muted">{{ Str::limit($item->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($item->color)
                                        <span class="badge" style="background-color: {{ $item->color }}; color: white;">
                                            {{ $item->color }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->required_poomsae_code && $item->required_poomsae_name)
                                        {{-- Display from cap_dai table (for KT1, KT2) --}}
                                        <small>{{ $item->required_poomsae_name }}</small>
                                        <br><code class="text-primary">{{ $item->required_poomsae_code }}</code>
                                    @else
                                        {{-- Display from baiQuyen relationship --}}
                                        @php $poomsae = $item->baiQuyen()->wherePivot('loai_quyen', 'bat_buoc')->orderByPivot('thu_tu_uu_tien')->first(); @endphp
                                        @if($poomsae)
                                            <small>{{ $poomsae->ten_bai_quyen_english ?? $poomsae->ten_bai_quyen_vietnamese }}</small>
                                            @if($poomsae->ten_bai_quyen_korean)
                                                <br><code class="text-primary">{{ $poomsae->ten_bai_quyen_korean }}</code>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    @endif
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">
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