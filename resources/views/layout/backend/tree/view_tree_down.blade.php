@php
use App\Helpers\BaseHelper;
$adminPrefix = BaseHelper::getAdminPrefix();
@endphp
@extends('layout.system.backend')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-2">
                    Quản lý Tree Down Logs
                </h1>
            </div>
            <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="javascript:void(0)">App</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Tree Down Logs
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
            <h3 class="block-title">Danh sách Tree Down Logs</h3>
        </div>
        <div class="block-content">
            <!-- Search và Filter -->
            <form method="GET" action="{{ route($adminPrefix . '.view-tree-down.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">UserName/Email/Tên</label>
                        <input
                            type="text"
                            class="form-control"
                            name="search"
                            placeholder="Nhập UserName, Email hoặc Tên..."
                            value="{{ $search }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Nhập FUserName</label>
                        <input
                            type="text"
                            class="form-control"
                            name="f_user_name"
                            placeholder="Nhập UserName FUser..."
                            value="{{ $fUserName }}">
                    </div>


                    <div class="col-md-2">
                        <label class="form-label">Tầng</label>
                        <input
                            type="number"
                            class="form-control"
                            name="indirect_id"
                            placeholder="IndirectID"
                            value="{{ $indirectId }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Từ ngày</label>
                        <input
                            type="date"
                            class="form-control"
                            name="from_date"
                            value="{{ $fromDate }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Đến ngày</label>
                        <input
                            type="date"
                            class="form-control"
                            name="to_date"
                            value="{{ $toDate }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search me-1"></i> Tìm kiếm
                            </button>
                        </div>
                    </div>
                </div>
            </form>


            <!-- Table -->
            <div class="table-scrollable-wrapper">
                <table class="table table-bordered table-striped table-vcenter table-scrollable">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">ID</th>
                            <th style="width: 100px;">UserID</th>
                            <th style="width: 150px;">UserName</th>
                            <th style="width: 150px;">Họ & Tên</th>
                            <th style="width: 200px;">Email</th>
                            <th style="width: 100px;">FUserName</th>
                            <th style="width: 100px;">Tầng</th>
                            <th style="width: 150px;">Cấp bậc (Chuyên nghiệp,đồng ....)</th>
                            <th style="width: 150px;">LevelID ( Danh hiệu GS,QL,GĐKD)</th>
                            <th style="width: 150px;">Doanh số cá nhân </th>
                            <th style="width: 150px;">Doanh số hệ thống</th>
                            <th style="width: 150px;"></th>Ngày tạo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($treeDownLogs as $log)
                        <tr>
                            <td class="text-center">{{ $log->ID }}</td>
                            <td class="text-center">
                                <strong>{{ $log->UserID }}</strong>
                            </td>
                            <td>
                                @if($log->user)
                                <strong>{{ $log->user->UserName }}</strong>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($log->user)
                                {{ $log->user->Email }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($log->user)
                                {{ $log->user->FullName }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <strong>{{ $log->FUserName }}</strong>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $log->IndirectID ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                @if($log->user && $log->user->node)
                                <span class="badge bg-primary">{{ $log->user->node->MucID ?? 0 }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($log->user && $log->user->node)
                                <span class="badge bg-success">{{ $log->user->node->LevelID ?? 0 }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($log->user && $log->user->node)
                                @php
                                $totalPV = $log->user->node->TotalPV ?? 0;
                                @endphp
                                <strong class="text-info">{{ number_format((float)$totalPV, 0, ',', '.') }}</strong>
                                {{-- Debug: {{ $totalPV }} --}}
                                @else
                                <span class="text-muted">0</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($log->user && $log->user->node)
                                @php
                                $totalPVSystem = $log->user->node->PVSystem ?? 0;
                                @endphp
                                <strong class="text-info">{{ number_format((float)$totalPVSystem, 0, ',', '.') }}</strong>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $log->DateCreate ? $log->DateCreate->format('d/m/Y H:i') : '-' }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3" style="opacity: 0.4;"></i>
                                    <span class="text-muted">Không có dữ liệu tree down logs</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="row mt-3">
                <div class="col-12">
                    {{ $treeDownLogs->appends(request()->input())->links('layout.system.partials.pagination') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection