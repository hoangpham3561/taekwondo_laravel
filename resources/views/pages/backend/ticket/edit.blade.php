@extends('layout.system.backend')

@section('content')
<div class="ticket-edit-page">
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
                    <li class="breadcrumb-item" aria-current="page">
                        Edit
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="content">
    @include('layout.system.partials.message')
    <div class="block block-rounded p-3">
        <div class="block-header block-header-default">
            <h3 class="block-title">Edit ticket</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-8 space-y-5">
                    <form action="{{ route('admin.tickets.update', ['ticket' => $data['id']]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="form-label" for="ticket-subject">Subject</label>
                            <input type="text" readonly class="form-control-plaintext" id="ticket-subject" name="subject" value="{{ $data['subject'] }}">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="ticket-email">Email</label>
                            <input type="text" readonly class="form-control-plaintext" id="ticket-email" name="email" value="{{ $data['email'] }}">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="ticket-message">Content</label>
                            <div class="form-control-plaintext border rounded px-3 py-2 ticket-message-body" id="ticket-message">{{ $data['message'] ?? '' }}</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="ticket-reply">Reply</label>
                            <textarea class="form-control" id="ticket-reply" name="reply" rows="10" placeholder="Nhập nội dung phản hồi…"></textarea>
                        </div>
                        <div class="mb-4">
                            <button type="submit" class="btn btn-primary">Reply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
