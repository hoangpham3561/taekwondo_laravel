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
                        Danh Sách Đơn Vị Của Câu Lạc Bộ
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded p-3">
            
        </div>
    </div>
@endsection