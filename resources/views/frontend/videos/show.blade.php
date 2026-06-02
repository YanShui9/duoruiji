@extends('layouts.app')

@section('title', $video->title . ' - 多瑞吉医学名家讲堂')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">首页</a></li>
            <li class="breadcrumb-item"><a href="{{ route('videos.index') }}">往期视频</a></li>
            <li class="breadcrumb-item active">{{ $video->title }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <!-- 视频播放器 -->
            <div class="card mb-4">
                <div class="card-body p-0">
                    <div id="video-player" style="width: 100%; height: 500px;"></div>
                </div>
            </div>

            <!-- 视频简介 -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $video->title }}</h5>
                    <span class="badge bg-secondary">{{ $video->formatted_duration }}</span>
                </div>
                <div class="card-body">
                    @if($video->lecture)
                        <p class="text-muted mb-2">
                            <i class="bi bi-collection"></i> 所属讲座：<a href="{{ route('lectures.show', $video->lecture) }}">{{ $video->lecture->title }}</a>
                        </p>
                    @endif
                    <p>{!! nl2br(e($video->description)) !!}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- 本期嘉宾 -->
            @if($experts->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">本期嘉宾</h5>
                </div>
                <div class="card-body">
                    @foreach($experts as $expert)
                    <div class="d-flex align-items-center mb-3">
                        @if($expert->avatar)
                            <img src="{{ Storage::url($expert->avatar) }}" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;" alt="{{ $expert->name }}">
                        @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-person text-muted"></i>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-0"><a href="{{ route('experts.show', $expert) }}">{{ $expert->name }}</a></h6>
                            <small class="text-muted">{{ $expert->title }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- 微信分享 -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">分享视频</h5>
                </div>
                <div class="card-body text-center">
                    <div id="qrcode" class="mb-3"></div>
                    <p class="text-muted small">微信扫码分享此视频</p>
                </div>
            </div>

            <!-- 相关视频 -->
            @if($relatedVideos->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">相关视频</h5>
                </div>
                <div class="card-body">
                    @foreach($relatedVideos as $related)
                    <div class="d-flex mb-3">
                        @if($related->cover_image)
                            <img src="{{ Storage::url($related->cover_image) }}" class="rounded me-3" style="width: 80px; height: 60px; object-fit: cover;" alt="{{ $related->title }}">
                        @else
                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 60px;">
                                <i class="bi bi-play-circle text-muted"></i>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-0"><a href="{{ route('videos.show', $related) }}">{{ Str::limit($related->title, 30) }}</a></h6>
                            <small class="text-muted">{{ $related->formatted_duration }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/plugins/ckplayer/ckplayer.js') }}"></script>
<script src="{{ asset('assets/plugins/phpqrcode/phpqrcode.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 初始化视频播放器
    var videoObject = {
        container: '#video-player',
        variable: 'player',
        autoplay: false,
        video: '{{ Storage::url($video->video_url) }}'
    };
    var player = new ckplayer(videoObject);

    // 生成二维码
    var url = window.location.href;
    if (document.getElementById('qrcode')) {
        new QRCode(document.getElementById('qrcode'), {
            text: url,
            width: 150,
            height: 150
        });
    }
});
</script>
@endpush
