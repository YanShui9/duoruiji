@extends('layouts.app')

@section('title', '往期视频 - 多瑞吉医学名家讲堂')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-5">往期视频</h1>

    <div class="row">
        @forelse($videos as $video)
        <div class="col-md-4 mb-4">
            <div class="card card-hover h-100">
                @if($video->cover_image)
                    <img src="{{ Storage::url($video->cover_image) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $video->title }}">
                @else
                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="bi bi-play-circle display-4 text-white"></i>
                    </div>
                @endif

                <div class="position-absolute bottom-0 end-0 badge bg-dark m-2">
                    {{ $video->formatted_duration }}
                </div>

                <div class="card-body">
                    <h5 class="card-title">{{ $video->title }}</h5>
                    @if($video->lecture)
                        <p class="card-text text-muted">
                            <small><i class="bi bi-collection"></i> {{ $video->lecture->title }}</small>
                        </p>
                    @endif
                    <p class="card-text">{{ Str::limit($video->description, 100) }}</p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('videos.show', $video) }}" class="btn btn-outline-primary w-100">
                        <i class="bi bi-play-circle"></i> 观看视频
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-camera-video display-1 text-muted"></i>
            <p class="mt-3 text-muted">暂无视频资源</p>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $videos->links() }}
    </div>
</div>
@endsection
