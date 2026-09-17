@php
use App\Helpers\BaseHelper;
    use App\Enums\CategoryStatusEnum;
    $statusGroup = $category->countBy('status');
    $totalStatus = array_sum($groupByStatus);
    $adminPrefix = BaseHelper::getAdminPrefix();
    $categoryPrefix = config('core.routes.category.prefix');
@endphp
@extends('layout.system.backend')
@section('css_after')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-2">
                        Category
                    </h1>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="javascript:void(0)">App</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Category
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
                <h3 class="block-title">List</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option">
                        <i class="mdi mdi-cog"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                    <div class="row">
{{--                        <div class="col-sm-8">--}}
{{--                            @if(!empty($statusGroup))--}}
{{--                                <div class="dt-buttons mb-3">--}}
{{--                                    <a href="{{ route($adminPrefix . '.' . $categoryPrefix . '.index') }}" class="dt-button buttons-copy buttons-html5 btn btn-sm btn-alt-primary" tabindex="0" aria-controls="DataTables_Table_3" type="button">--}}
{{--                                        <span>Tất cả</span>--}}
{{--                                        <span class="nav-main-link-badge badge rounded-pill bg-primary">{{ $totalStatus }}</span>--}}
{{--                                    </a>--}}
{{--                                    @foreach($groupByStatus as $key => $value)--}}
{{--                                        @php--}}
{{--                                            $btnClass = '';--}}
{{--                                            $bgClass = '';--}}
{{--                                            if ($key === CategoryStatusEnum::ACTIVE) {--}}
{{--                                                $btnClass = 'btn-alt-info';--}}
{{--                                                $bgClass = 'bg-info';--}}
{{--                                            }--}}

{{--                                            if ($key === CategoryStatusEnum::INACTIVE) {--}}
{{--                                                $btnClass = 'btn-alt-success';--}}
{{--                                                $bgClass = 'bg-success';--}}
{{--                                            }--}}
{{--                                            if ($key === CategoryStatusEnum::DELETED) {--}}
{{--                                                $btnClass = 'btn-alt-danger';--}}
{{--                                                $bgClass = 'bg-danger';--}}
{{--                                            }--}}
{{--                                        @endphp--}}
{{--                                        <a href="{{ route($adminPrefix . '.' . $categoryPrefix . '.index') }}?status={{ $key }}" class="dt-button buttons-csv buttons-html5 btn btn-sm {{ $btnClass }}" tabindex="0" aria-controls="DataTables_Table_3" type="button">--}}
{{--                                    <span>{{ ucfirst($key) }}--}}
{{--                                    <span class="nav-main-link-badge badge rounded-pill {{ $bgClass }}"> {{ $value }}</span>--}}
{{--                                        </a>--}}
{{--                                    @endforeach--}}
{{--                                </div>--}}
{{--                            @endif--}}
{{--                        </div>--}}
                        <div class="col-sm-12 mb-3">
                            <a href="{{ route($adminPrefix . '.' . $categoryPrefix . '.create') }}" class="btn btn-sm btn-outline-primary float-end">Add New</a>
                        </div>
                    </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 100px;">
                                ID
                            </th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th style="width: 15%;">Status</th>
                            <th style="width: 15%;">DateCreated</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($category))
                            @foreach($category as $item)
                                @include('pages.backend.category.table', ['item' => $item, 'concat' => ''])
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="7">Not found Data !</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    {{ $category->appends(request()->input())->links('layout.system.partials.pagination') }}
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
                fireDeleteConfirmPopup($(this.closest("form")).attr('name'), "Xóa cc", "cc mày chắc chưa?", "Ko hối hận");
            });
        });
    </script>
@endsection
