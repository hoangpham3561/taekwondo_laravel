@extends('layout.system.backend')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-2">
                        News Edit ({{ $data['title'] }})
                    </h1>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="javascript:void(0)">App</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            News
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
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Add News</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-lg-8 space-y-5">
                        <form action="{{ route('admin.news.update', ['news' => $data['id']]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row push">
                                <div class="col-lg-4">

                                </div>
                                <div class="col-lg-8 col-xl-5">
                                    <div class="mb-4">
                                        <label class="form-label" for="example-text-input">Title</label>
                                        <input type="text" class="form-control" id="example-text-input" name="title" value="{{ $data['title'] }}" placeholder="Please enter value category">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="example-email-input">Slug</label>
                                        <input type="text" class="form-control" id="example-email-input" name="slug" value="{{ $data['slug'] }}" placeholder="Please enter value slug">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="example-email-input">Image</label>
                                        <input type="file" class="form-control" id="example-email-input" name="images">
                                        @if(!empty($data['images']))
                                            <img class="mt-2" width="150" src="{{ asset('storage/upload/' . $data['images']) }}" alt="{{ $data['title'] }}">
                                        @endif
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="example-text-input">Content</label>
                                        <textarea class="form-control" name="content" rows="4" placeholder="Textarea content..">{{ $data['content'] }}</textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="example-select">Choose Category</label>
                                        @if(!empty($recursiveCategories))
                                            <select class="form-select" id="example-select" name="cate_id">
                                                @foreach($recursiveCategories as $key => $item)
                                                    <option value="{{ $key }}" @if($key == $data['cate_id']) selected @endif>{{ $item['name'] }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="example-text-input">Seo Title</label>
                                        <input type="text" class="form-control" id="example-text-input" name="seo_title" value="{{ $data['seo_title'] }}" placeholder="Please enter value category">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="example-text-input">Seo Description</label>
                                        <textarea class="form-control" name="seo_description" rows="4" placeholder="Textarea content..">{{ $data['seo_description'] }}</textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="example-select">Status</label>
                                        @php
                                            unset($newsStatus[2]);
                                            $statusName = '';
                                        @endphp
                                        @if(!empty($newsStatus))
                                            <select class="form-select" id="example-select" name="status">
                                                @foreach($newsStatus as $key => $status)
                                                    <option value="{{ $status }}" @if($status == $data['status']) selected @endif>
                                                        @if($status == 'active') {{ $statusName = 'Active' }} @else {{ $statusName = 'Inactive' }} @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="mb-4">
                                        <button type="submit" class="btn btn-primary">Edit News</button>
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
@section('js_after')
    <script src="//cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('content', {
            filebrowserUploadUrl: "{{ route('admin.ckeditor.upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form'
        });
    </script>
@endsection
