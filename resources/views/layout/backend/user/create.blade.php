@extends('layout.system.backend')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-2">
                        User Add
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
                        <li class="breadcrumb-item" aria-current="page">
                            Add
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
                <h3 class="block-title">Add User</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-lg-8 space-y-5">
                        <form action="{{ route('admin.users.store') }}" method="POST">
                            @csrf
                            <div class="row push">
                                <div class="col-lg-4">

                                </div>
                                <div class="col-lg-8 col-xl-5">
                                    <div class="mb-4">
                                        <label class="form-label" for="example-text-input">Username</label>
                                        <input type="text" class="form-control" id="example-text-input" name="username" value="{{ old('username') }}" placeholder="Please enter value username">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="example-email-input">Email</label>
                                        <input type="email" class="form-control" id="example-email-input" name="email" value="{{ old('email') }}" placeholder="Please enter value email">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="example-text-input">Fullname</label>
                                        <input type="text" class="form-control" id="example-text-input" name="name" value="{{ old('name') }}" placeholder="Please enter value name">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="example-select">Status</label>
                                        @php
                                            unset($userStatus[2]);
                                            $statusName = '';
                                        @endphp
                                        @if(!empty($userStatus))
                                            <select class="form-select" id="example-select" name="status">
                                                @foreach($userStatus as $key => $status)
                                                    <option value="{{ $status }}">
                                                        @if($status == 'active') {{ $statusName = 'Active' }} @else {{ $statusName = 'Inactive' }} @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="example-text-input">2FA on off</label>
                                        <select class="form-select" id="example-select" name="is_lock_2fa">
                                            <option value="1">Lock</option>
                                            <option value="0">Unlock</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="example-text-input">Is lock login</label>
                                        <select class="form-select" id="example-select" name="is_lock_login">
                                            <option value="1">Lock</option>
                                            <option value="0">Unlock</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="example-text-input">Reference id</label>
                                        <input type="text" class="form-control" id="example-text-input" name="ref_id" value="{{ old('ref_id') }}" placeholder="Please enter value reference id">
                                    </div>
                                    <div class="mb-4">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
