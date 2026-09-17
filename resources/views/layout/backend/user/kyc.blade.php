@php
use App\Helpers\BaseHelper;
    use App\Enums\UserStatusEnum;

    $adminPrefix = BaseHelper::getAdminPrefix();
    $userPrefix = config('core.routes.user.prefix');
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
                        User
                    </h1>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="javascript:void(0)">App</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            User
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
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 20px;">
                                ID
                            </th>
                            <th style="width: 120px;">Name</th>
                            <th style="width: 200px;">Email</th>
                            <th style="width: 120px;">Username</th>
                            <th style="width: 102px;">Status</th>
                            <th style="width: 120px;">KYC Status</th>
                            <th style="width: 180px">Decline reason</th>
                            <th class="text-center" style="width: 150px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($users))
                            @foreach($users as $item)
                                <tr>
                                    <td class="text-center">
                                        {{ $item->id }}
                                    </td>
                                    <td class="fw-semibold fs-sm">
                                        <a>{{ $item->name }}</a>
                                    </td>
                                    <td class="fs-sm">{{ $item->email }}</td>
                                    <td class="fs-sm">{{ $item->username }}</td>
                                    <td>
                                        @php
                                            $class = '';
                                            if ($item->status === UserStatusEnum::ACTIVE) {
                                                $class = 'bg-info-light text-info';
                                            }
                                            if ($item->status === UserStatusEnum::INACTIVE) {
                                                $class = 'bg-warning-light text-warning';
                                            }
                                        @endphp
                                        <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill {{ $class }}">{{ $item->status }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $class = '';
                                            if ($item->kyc_status === 'Y') {
                                                $class = 'bg-info-light text-info';
                                            }
                                            if ($item->kyc_status === 'N') {
                                                $class = 'bg-warning-light text-warning';
                                            }
                                        @endphp
                                        <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill {{ $class }}">{{ $item->kyc_status }}</span>
                                    </td>
                                    <td>{{ $item->decline_reason }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route($adminPrefix . '.' . $userPrefix . '.kycDetail', ['id' => $item->id ]) }}" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip" title="" data-bs-original-title="Edit">
                                                <i class="mdi mdi-pencil"></i>
                                                Duyệt KYC
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    {{ $users->appends(request()->input())->links('layout.system.partials.pagination') }}
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
                fireDeleteConfirmPopup($(this.closest("form")).attr('name'), "Bạn có chắc chắn muốn xóa người dùng này không?", "Người dùng này sẽ bị xóa và không thể khôi phục lại.", "Thực hiện");
            });
        });
    </script>
@endsection
