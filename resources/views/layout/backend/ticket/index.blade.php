@php
use App\Helpers\BaseHelper;
use App\Enums\TicketStatusEnum;

$statusGroup = $tickets->countBy('status');
$adminPrefix = BaseHelper::getAdminPrefix();
$ticketPrefix = config('core.routes.tickets.prefix');
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
                        Ticket
                    </h1>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="javascript:void(0)">App</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Ticket
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
                <div class="table-responsive">
                    @if(!empty($statusGroup))
                        <div class="dt-buttons mb-3">
                            <a href="{{ route($adminPrefix . '.' . $ticketPrefix . '.index') }}" class="dt-button buttons-copy buttons-html5 btn btn-sm btn-alt-primary" tabindex="0" aria-controls="DataTables_Table_3" type="button">
                                <span>Tất cả</span>
                                <span class="nav-main-link-badge badge rounded-pill bg-primary">{{ $tickets->total() }}</span>
                            </a>
                            @foreach($groupByStatus as $key => $value)
                                @php
                                    $btnClass = 'btn-alt-secondary';
                                    $bgClass = 'bg-secondary';
                                    if ($key === TicketStatusEnum::NEW) {
                                        $btnClass = 'btn-alt-info';
                                        $bgClass = 'bg-info';
                                    }
                                    if ($key === TicketStatusEnum::READ) {
                                        $btnClass = 'btn-alt-primary';
                                        $bgClass = 'bg-primary';
                                    }
                                    if ($key === TicketStatusEnum::REPLIED) {
                                        $btnClass = 'btn-alt-success';
                                        $bgClass = 'bg-success';
                                    }
                                    if ($key === TicketStatusEnum::CLOSED) {
                                        $btnClass = 'btn-alt-warning';
                                        $bgClass = 'bg-warning';
                                    }
                                @endphp
                                <a href="{{ route($adminPrefix . '.' . $ticketPrefix . '.index') }}?status={{ $key }}" class="dt-button buttons-csv buttons-html5 btn btn-sm {{ $btnClass }}" tabindex="0" aria-controls="DataTables_Table_3" type="button">
                                    <span>{{ ucfirst($key) }}
                                    <span class="nav-main-link-badge badge rounded-pill {{ $bgClass }}"> {{ $value }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 100px;">
                                ID
                            </th>
                            <th>Subject</th>
                            <th style="width: 30%;">Email</th>
                            <th style="width: 15%;">Status</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($tickets))
                            @foreach($tickets as $ticket)
                            <tr>
                                <td class="text-center">
                                    {{ $loop->index + 1 }}
                                </td>
                                <td class="fw-semibold fs-sm">
                                    <a>{{ $ticket->subject }}</a>
                                </td>
                                <td class="fs-sm">{{ $ticket->email }}</td>
                                <td>
                                    @php
                                        $class = '';
                                        if ($ticket->status === TicketStatusEnum::NEW) {
                                            $class = 'bg-info-light text-info';
                                        }
                                        if ($ticket->status === TicketStatusEnum::READ) {
                                            $class = 'bg-primary-light text-primary';
                                        }
                                        if ($ticket->status === TicketStatusEnum::REPLIED) {
                                            $class = 'bg-success-light text-success';
                                        }
                                        if ($ticket->status === TicketStatusEnum::CLOSED) {
                                            $class = 'bg-warning-light text-warning';
                                        }
                                    @endphp
                                    <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill {{ $class }}">{{ $ticket->status }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route($adminPrefix . '.' . $ticketPrefix . '.edit', ['ticket' => $ticket->id ]) }}" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip" title="" data-bs-original-title="Edit">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <form name="ticketForm_{{ $ticket->id }}" action="{{ route($adminPrefix . '.' . $ticketPrefix . '.destroy', ['ticket' => $ticket->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <a class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-custom-delete" data-bs-toggle="tooltip" title="" data-bs-original-title="Delete">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    {{ $tickets->appends(request()->input())->links('layout.system.partials.pagination') }}
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
                fireDeleteConfirmPopup($(this.closest("form")).attr('name'), "Bạn có chắc chắn muốn xóa ticket này không?", "Ticket này sẽ bị xóa và không thể khôi phục lại.", "Thực hiện");
            });
        });
    </script>
@endsection
