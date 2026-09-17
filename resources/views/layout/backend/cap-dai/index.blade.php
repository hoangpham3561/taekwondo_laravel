@extends('layout.system.backend')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title mb-1">Cấp Đai</h3>
            <ul class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Cấp Đai</li>
            </ul>
        </div>
    </div>
</div>

@if(!empty($error))
    <div class="alert alert-danger">
        {{ $error }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width: 80px;">#</th>
                        <th>Tên cấp đai</th>
                        <th class="text-end" style="width: 160px;">Thứ tự</th>
                        <th class="text-end" style="width: 220px;">Số bài quyền</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($capDai ?? [] as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name ?? ($item->ten ?? '') }}</td>
                            <td class="text-end">{{ $item->order_sequence ?? '' }}</td>
                            <td class="text-end">{{ $item->baiQuyen?->count() ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Chưa có dữ liệu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
