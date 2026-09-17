@extends('layout.frontend.frontend')

@section('content')
<section class="breadcrumb-section set-bg" data-setbg="{{ asset('client/gymlife/img/breadcrumb-bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb-text">
                    <h2>Chi tiết tin tức</h2>
                    <div class="bt-option">
                        <a href="{{ route($userPrefix . '.index') }}">Trang chủ</a>
                        <a href="{{ route($userPrefix . '.new') }}">Tin tức</a>
                        <span>Chi tiết</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="blog-details-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 p-0">
                <div class="blog-details-text">
                    @php
                        $publishedDate = $news->published_at
                            ? $news->published_at->format('d/m/Y')
                            : optional($news->created_at)->format('d/m/Y');
                        $imageUrl = $news->featured_image_url;
                        if (empty($imageUrl) && !empty($news->images)) {
                            $imageUrl = asset('storage/upload/' . $news->images);
                        }
                        $imageUrl = $imageUrl ?: asset('client/gymlife/img/blog/blog-1.jpg');
                    @endphp
                    <div class="blog-details-title">
                        <h2>{{ $news->title }}</h2>
                        <div class="blog-details-author">
                            <div class="ba-text">
                                <h5>{{ optional($news->category)->name ?? 'Tin tức' }}</h5>
                                <span>{{ $publishedDate }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="blog-details-pic">
                        <img src="{{ $imageUrl }}" alt="{{ $news->title }}">
                    </div>
                    <div class="blog-details-desc">
                        {!! $news->content !!}
                    </div>
                </div>

                @if($relatedNews->isNotEmpty())
                    <div class="latest-blog mt-5">
                        <h4>Bài viết liên quan</h4>
                        <div class="row">
                            @foreach($relatedNews as $item)
                                @php
                                    $relatedImage = $item->featured_image_url;
                                    if (empty($relatedImage) && !empty($item->images)) {
                                        $relatedImage = asset('storage/upload/' . $item->images);
                                    }
                                    $relatedImage = $relatedImage ?: asset('client/gymlife/img/latest-blog/lb-1.jpg');
                                @endphp
                                <div class="col-md-6">
                                    <div class="lb-item">
                                        <div class="lb-pic">
                                            <img src="{{ $relatedImage }}" alt="{{ $item->title }}">
                                        </div>
                                        <div class="lb-text">
                                            <h6>
                                                <a href="{{ route($userPrefix . '.new-detail', ['id' => $item->id]) }}">{{ $item->title }}</a>
                                            </h6>
                                            <span>{{ optional($item->published_at)->format('d/m/Y') ?? optional($item->created_at)->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4 col-md-8 p-0">
                <div class="sidebar-option">
                    <div class="so-categories">
                        <h5 class="title">Danh mục</h5>
                        <ul>
                            @forelse($categoryStats as $category)
                                <li>
                                    <a href="{{ route($userPrefix . '.new') }}">
                                        {{ $category->name }}
                                        <span>{{ $category->news_count }}</span>
                                    </a>
                                </li>
                            @empty
                                <li><a href="{{ route($userPrefix . '.new') }}">Tin tức <span>0</span></a></li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="so-tags">
                        <h5 class="title">Từ khóa</h5>
                        @forelse($suggestedKeywords as $keyword)
                            <a href="{{ route($userPrefix . '.new') }}?keyword={{ urlencode($keyword) }}">{{ $keyword }}</a>
                        @empty
                            <a href="{{ route($userPrefix . '.new') }}">Taekwondo</a>
                            <a href="{{ route($userPrefix . '.new') }}">Poomsae</a>
                            <a href="{{ route($userPrefix . '.new') }}">Thi đấu</a>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
