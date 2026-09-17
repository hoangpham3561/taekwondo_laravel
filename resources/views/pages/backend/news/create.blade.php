@extends('layout.system.backend')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-2">
                        News Add
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
                            Add
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
                <h3 class="block-title">Add News</h3>
            </div>
            <div class="block-content block-content-full">
                <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="news-create-form">
                    @csrf
                    @method('POST')
                    <div class="row g-4">
                        <div class="col-xl-8">
                            <div class="mb-4">
                                <label class="form-label" for="news-title">Title</label>
                                <input type="text" class="form-control" id="news-title" name="title" value="{{ old('title') }}" placeholder="Please enter news title">
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="news-slug">Slug</label>
                                <input type="text" class="form-control" id="news-slug" name="slug" value="{{ old('slug') }}" placeholder="Auto generated from title" readonly>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="news-content">Content</label>
                                <textarea class="form-control" id="news-content" name="content" rows="8" placeholder="Textarea content..">{{ old('content') }}</textarea>
                            </div>
                            <div class="mb-0">
                                <label class="form-label" for="news-seo-description">Seo Description</label>
                                <textarea class="form-control" id="news-seo-description" name="seo_description" rows="4" placeholder="Please enter seo description">{{ old('seo_description') }}</textarea>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="block block-rounded bg-body-light mb-0 p-3">
                                <div class="block-content py-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="news-image">Image</label>
                                        <input type="file" class="form-control" id="news-image" name="images">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="news-category">Choose Category</label>
                                        @if(!empty($recursiveCategories))
                                            <select class="form-select" id="news-category" name="cate_id">
                                                <option value="" {{ old('cate_id') ? '' : 'selected' }} disabled>-- Chọn danh mục --</option>
                                                @foreach($recursiveCategories as $key => $item)
                                                    <option value="{{ $key }}" {{ old('cate_id') == $key ? 'selected' : '' }}>{{ $item['name'] }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="news-seo-title">Seo Title</label>
                                        <input type="text" class="form-control" id="news-seo-title" name="seo_title" value="{{ old('seo_title') }}" placeholder="Please enter seo title">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="news-status">Status</label>
                                        <select class="form-select" id="news-status" name="status">
                                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Add News</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('css_after')
    <style>
        .news-create-form .form-control,
        .news-create-form .form-select {
            color: #e6edf3;
        }

        .news-create-form .form-control::placeholder {
            color: #8b949e;
            opacity: 1;
        }
    </style>
@endsection
@section('js_after')
    <script src="//cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('news-content', {
            filebrowserUploadUrl: "{{ route('admin.ckeditor.upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form'
        });

        function toSlug(value) {
            return value
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/đ/g, 'd')
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }

        const titleInput = document.getElementById('news-title');
        const slugInput = document.getElementById('news-slug');

        if (titleInput && slugInput) {
            const syncSlug = function () {
                slugInput.value = toSlug(titleInput.value);
            };

            titleInput.addEventListener('input', syncSlug);

            if (!slugInput.value.trim()) {
                syncSlug();
            }
        }
    </script>
@endsection
