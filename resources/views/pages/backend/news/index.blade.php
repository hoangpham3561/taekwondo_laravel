@php
use App\Helpers\BaseHelper;
    use App\Enums\NewsStatusEnum;

    $statusGroup = $news->countBy('status');
    $adminPrefix = BaseHelper::getAdminPrefix();
    $newsPrefix = config('core.routes.news.prefix');
@endphp
@extends('layout.backend.backend')
@section('css_after')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-2">
                        Tin Tức
                    </h1>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route($adminPrefix . '.dashboard') }}">Trang Chủ</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Tin Tức
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        @include('layout.backend.partials.message')
        <div class="block block-rounded p-3">
            <div class="block-header block-header-default">
                <h3 class="block-title">Danh Sách</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option">
                        <i class="mdi mdi-cog"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="table-responsive">
                            @if(!empty($statusGroup))
                                <div class="dt-buttons mb-3">
                                    <a href="{{ route($adminPrefix . '.' . $newsPrefix . '.index') }}" class="dt-button buttons-copy buttons-html5 btn btn-sm btn-alt-primary" tabindex="0" aria-controls="DataTables_Table_3" type="button">
                                        <span>Tất cả</span>
                                        <span class="nav-main-link-badge badge rounded-pill bg-primary">{{ $news->total() }}</span>
                                    </a>
                                    @foreach($groupByStatus as $key => $value)
                                        @php
                                            $btnClass = '';
                                            $bgClass = '';
                                            $statusText = '';
                                            if ($key === NewsStatusEnum::ACTIVE) {
                                                $btnClass = 'btn-alt-info';
                                                $bgClass = 'bg-info';
                                                $statusText = 'Đang Hoạt Động';
                                            }

                                            if ($key === NewsStatusEnum::INACTIVE) {
                                                $btnClass = 'btn-alt-success';
                                                $bgClass = 'bg-success';
                                                $statusText = 'Không Hoạt Động';
                                            }

                                        @endphp
                                        <a href="{{ route($adminPrefix . '.' . $newsPrefix . '.index') }}?status={{ $key }}" class="dt-button buttons-csv buttons-html5 btn btn-sm {{ $btnClass }}" tabindex="0" aria-controls="DataTables_Table_3" type="button">
                                            <span>{{ $statusText ?: ucfirst($key) }}
                                            <span class="nav-main-link-badge badge rounded-pill {{ $bgClass }}"> {{ $value }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <a href="{{ route($adminPrefix . '.' . $newsPrefix . '.create') }}" class="btn btn-sm btn-outline-primary float-end">Thêm Mới</a>
                    </div>
                </div>
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 100px;">
                                ID
                            </th>
                            <th>Tiêu Đề</th>
                            <th>Danh Mục</th>
                            <th style="width: 10%;">Hình Ảnh</th>
                            <th style="width: 15%;">Trạng Thái</th>
                            <th style="width: 15%;">Ngày Tạo</th>
                            <th class="text-center" style="width: 100px;">Thao Tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($news))
                            @foreach($news as $item)
                                <tr>
                                    <td class="text-center">
                                        {{ $item->id }}
                                    </td>
                                    <td class="fw-semibold fs-sm">
                                        <a>{{ $item->title }}</a>
                                    </td>
                                    <td class="fs-sm">{{ optional($item->category)->name ?? 'Chưa phân loại' }}</td>
                                    <td><img width="150" src="{{ asset('storage/upload/' . $item->images) }}" alt="{{ $item->title }}"></td>
                                    <td>
                                        @php
                                            $class = '';
                                            $statusText = '';
                                            if ($item->status === NewsStatusEnum::ACTIVE) {
                                                $class = 'bg-info-light text-info';
                                                $statusText = 'Đang Hoạt Động';
                                            }
                                            if ($item->status === NewsStatusEnum::INACTIVE) {
                                                $class = 'bg-warning-light text-warning';
                                                $statusText = 'Không Hoạt Động';
                                            }
                                            if ($item->status === NewsStatusEnum::DELETED) {
                                                $class = 'bg-danger-light text-danger';
                                                $statusText = 'Đã Xóa';
                                            }
                                        @endphp
                                        <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill {{ $class }}">{{ $statusText ?: $item->status }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($item['created_at'])->format('Y-m-d H:i:s') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route($adminPrefix . '.' . $newsPrefix . '.edit', ['news' => $item->id ]) }}" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip" title="" data-bs-original-title="Sửa">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            @if($item->status !== \App\Enums\NewsStatusEnum::DELETED)
                                                <form name="ticketForm_{{ $item->id }}" action="{{ route($adminPrefix . '.' . $newsPrefix . '.destroy', ['news' => $item->id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-custom-delete" data-bs-toggle="tooltip" title="" data-bs-original-title="Xóa">
                                                        <i class="mdi mdi-delete"></i>
                                                    </a>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    {{ $news->appends(request()->input())->links('layout.backend.partials.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js_after')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".btn-custom-delete").on('click', function() {
                fireDeleteConfirmPopup($(this.closest("form")).attr('name'), "Bạn có chắc chắn muốn xóa tin tức này không?", "Tin tức này sẽ bị xóa và không thể khôi phục lại.", "Thực hiện");
            });
        });
    </script>
@endsection
