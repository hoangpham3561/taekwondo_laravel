@extends('layout.system.backend')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-2">
                        Category Edit ( {{ $data['name'] }} )
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
                <h3 class="block-title">Edit Category</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-lg-8 space-y-5">
                        <form action="{{ route('admin.category.update', ['category' => $data['id']]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row push">
                                <div class="col-lg-4">
                                </div>
                                <div class="col-lg-8 col-xl-5">
                                    <div class="mb-4">
                                        <label class="form-label" for="example-text-input">Category name</label>
                                        <input type="text" class="form-control" id="example-text-input" name="name" value="{{ $data['name'] }}" placeholder="Please enter value category">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="example-email-input">Slug</label>
                                        <input type="text" class="form-control" id="example-email-input" name="slug" value="{{ $data['slug'] }}" placeholder="Please enter value slug">
                                    </div>
                                    @if(!empty($recursiveCategories))
                                        <div class="mb-4">
                                            <label class="form-label" for="example-select">Parent Category</label>
                                                <select class="form-select" id="example-select" name="parent_id">
                                                    @foreach($recursiveCategories as $key => $item)
                                                        <option value="{{ $key }}" @if($key == $data['id']) selected @endif>{{ $item['name'] }}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                    @endif
                                    <div class="mb-4">
                                        <label class="form-label" for="example-select">Status</label>
                                        @php
                                          unset($categoryStatus[2]);
                                          $statusName = '';
                                        @endphp
                                        @if(!empty($categoryStatus))
                                            <select class="form-select" id="example-select" name="status">
                                                @foreach($categoryStatus as $key => $status)
                                                    <option value="{{ $status }}" @if($status == $data['status']) selected @endif>
                                                        @if($status == 'active') {{ $statusName = 'Active' }} @else {{ $statusName = 'Inactive' }} @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="mb-4">
                                        <button type="submit" class="btn btn-primary">Edit Category</button>
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
