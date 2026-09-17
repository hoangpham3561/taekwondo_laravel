@extends('layout.frontend.frontend')

@section('content')
<section class="breadcrumb-section set-bg" data-setbg="{{ asset('client/gymlife/img/breadcrumb-bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb-text">
                    <h2>Tin tức</h2>
                    <div class="bt-option">
                        <a href="{{ route('.index') }}">Trang chủ</a>
                        <span>Tin tức</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="classes-section spad">
    <div class="container">
        <div class="section-title">
            <span>Our Blog</span>
            <h2>TIN TUC VA SU KIEN</h2>
        </div>

        @if($featured)
            @php
                $featuredDate = $featured->published_at
                    ? $featured->published_at->format('d/m/Y')
                    : optional($featured->created_at)->format('d/m/Y');
                $featuredImage = $featured->featured_image_url;
                if (empty($featuredImage) && !empty($featured->images)) {
                    $featuredImage = asset('storage/upload/' . $featured->images);
                }
                $featuredImage = $featuredImage ?: asset('client/gymlife/img/blog/blog-1.jpg');
            @endphp
            <div class="class-details-text mb-5 news-featured-box">
                <div class="cd-pic">
                    <img src="{{ $featuredImage }}" alt="{{ $featured->title }}">
                </div>
                <div class="cd-text">
                    <div class="cd-single-item">
                        <h3>{{ $featured->title }}</h3>
                        <p>{{ $featured->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($featured->content), 220) }}</p>
                        <p><strong>{{ $featuredDate }}</strong></p>
                        <a href="{{ route($userPrefix . '.new-detail', ['id' => $featured->id]) }}" class="primary-btn">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        @endif

        <ul class="nav nav-pills justify-content-center mb-4 news-tabs" id="news-tab" role="tablist">
            <li class="nav-item mx-1">
                <a class="nav-link active" id="tab-all" data-toggle="pill" href="#pane-all" role="tab" aria-controls="pane-all" aria-selected="true">Tất cả</a>
            </li>
            <li class="nav-item mx-1">
                <a class="nav-link" id="tab-event" data-toggle="pill" href="#pane-event" role="tab" aria-controls="pane-event" aria-selected="false">Sự kiện</a>
            </li>
            <li class="nav-item mx-1">
                <a class="nav-link" id="tab-achievement" data-toggle="pill" href="#pane-achievement" role="tab" aria-controls="pane-achievement" aria-selected="false">Thành tích</a>
            </li>
            <li class="nav-item mx-1">
                <a class="nav-link" id="tab-news" data-toggle="pill" href="#pane-news" role="tab" aria-controls="pane-news" aria-selected="false">Tin tức</a>
            </li>
            <li class="nav-item mx-1">
                <a class="nav-link" id="tab-announcement" data-toggle="pill" href="#pane-announcement" role="tab" aria-controls="pane-announcement" aria-selected="false">Thông báo</a>
            </li>
        </ul>

        <div class="tab-content" id="news-tab-content">
            @foreach(['all', 'event', 'achievement', 'news', 'announcement'] as $tabKey)
                <div class="tab-pane fade {{ $tabKey === 'all' ? 'show active' : '' }}" id="pane-{{ $tabKey }}" role="tabpanel">
                    <div class="row g-4">
                        @forelse($newsByCategory[$tabKey] ?? [] as $item)
                            @php
                                $itemDate = $item->published_at ? $item->published_at->format('d/m/Y') : optional($item->created_at)->format('d/m/Y');
                                $itemImage = $item->featured_image_url;
                                if (empty($itemImage) && !empty($item->images)) {
                                    $itemImage = asset('storage/upload/' . $item->images);
                                }
                                $itemImage = $itemImage ?: asset('client/gymlife/img/blog/blog-2.jpg');
                                $itemCategory = $item->category->name ?? ucfirst($tabKey);
                            @endphp
                            <div class="col-12 col-md-6 col-lg-4">
                                <article class="class-item h-100">
                                    <div class="ci-pic">
                                        <img src="{{ $itemImage }}" alt="{{ $item->title }}">
                                    </div>
                                    <div class="ci-text">
                                        <span>{{ $itemCategory }} - {{ $itemDate }}</span>
                                        <h5><a href="{{ route($userPrefix . '.new-detail', ['id' => $item->id]) }}">{{ $item->title }}</a></h5>
                                        <p>{{ $item->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($item->content), 100) }}</p>
                                    </div>
                                </article>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info mb-0">Chưa có bài viết.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection