@extends('layouts.app')

@section('title', $video->title . ' - 多瑞吉医学名家讲堂')

@section('content')
<!-- 面包屑导航 -->
<section style="padding: 1.5rem 0; background: var(--color-bg-warm); margin-top: 80px;">
    <div class="container" style="max-width: 1200px;">
        <div class="d-flex align-items-center justify-content-between">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" style="color: var(--color-text-secondary); text-decoration: none; font-size: 0.9rem;">首页</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('videos.index') }}" style="color: var(--color-text-secondary); text-decoration: none; font-size: 0.9rem;">往期视频</a>
                    </li>
                    <li class="breadcrumb-item active" style="color: var(--color-text); font-size: 0.9rem;">{{ Str::limit($video->title, 30) }}</li>
                </ol>
            </nav>
            <a href="{{ route('videos.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> 返回
            </a>
        </div>
    </div>
</section>

<div style="padding: var(--section-padding) 0;">
    <div class="container" style="max-width: 1200px;">
        <div class="row g-4">
            <div class="col-lg-8">
                <!-- 视频播放器 -->
                <div class="lecture-card mb-4 overflow-hidden">
                    @if($video->video_url && \Storage::disk('public')->exists($video->video_url))
                        <div id="video-player" style="width: 100%; aspect-ratio: 16/9; background: #000;"></div>
                    @else
                        <div style="width: 100%; aspect-ratio: 16/9; background: linear-gradient(135deg, #1a3a2a 0%, #2d5a3d 100%); display: flex; flex-direction: column; align-items: center; justify-content: center; color: #fff;">
                            <i class="bi bi-camera-video-off" style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.7;"></i>
                            <h5 style="margin-bottom: 0.5rem;">视频暂不可用</h5>
                            <p style="opacity: 0.8; font-size: 0.9rem;">该视频文件尚未上传或已丢失</p>
                        </div>
                    @endif
                </div>

                <!-- 视频简介 -->
                <div class="lecture-card">
                    <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--color-bg-warm); display: flex; justify-content: space-between; align-items: center;">
                        <h2 class="font-serif fw-semibold mb-0" style="font-size: 1.5rem;">{{ $video->title }}</h2>
                        <span style="background: var(--color-bg-warm); color: var(--color-text-secondary); font-weight: 600; padding: 0.5rem 1.25rem; border-radius: var(--radius-pill); font-size: 0.85rem;">
                            <i class="bi bi-clock me-1"></i>
                            {{ $video->formatted_duration }}
                        </span>
                    </div>
                    <div style="padding: 2rem;">
                        @if($video->lecture)
                            <div class="d-flex align-items-center mb-4 p-3 rounded-3" style="background: var(--color-bg-warm);">
                                <i class="bi bi-collection me-3" style="font-size: 1.25rem; color: var(--color-sage);"></i>
                                <div>
                                    <small style="color: var(--color-text-secondary); display: block; font-size: 0.8rem;">所属讲座</small>
                                    <a href="{{ route('lectures.show', $video->lecture) }}" style="color: var(--color-text); text-decoration: none; font-weight: 600;">
                                        {{ $video->lecture->title }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        <div style="line-height: 1.9; color: var(--color-text-secondary); font-size: 1.05rem;">
                            {!! preg_replace('/<(\w+)\s[^>]*>/', '<$1>', strip_tags($video->description, '<p><br><strong><em><b><i><ul><ol><li><h1><h2><h3><h4><h5><h6>')) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- 本期嘉宾 -->
                @if($experts->count() > 0)
                <div class="lecture-card mb-4">
                    <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--color-bg-warm);">
                        <h5 class="font-serif fw-semibold mb-0" style="font-size: 1.25rem;">
                            <i class="bi bi-people me-2" style="color: var(--color-sage);"></i>
                            本期嘉宾
                        </h5>
                    </div>
                    <div style="padding: 1.5rem 2rem;">
                        @foreach($experts as $expert)
                        <div class="d-flex align-items-center {{ !$loop->last ? 'mb-3 pb-3' : '' }}"
                             style="{{ !$loop->last ? 'border-bottom: 1px solid var(--color-bg-warm);' : '' }}">
                            @if($expert->avatar)
                                <img src="{{ $expert->thumb_avatar }}" class="rounded-circle me-3"
                                     style="width: 50px; height: 50px; object-fit: cover;"
                                     alt="{{ $expert->name }}">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                     style="width: 50px; height: 50px; background: var(--color-sage-light); color: #fff;">
                                    <i class="bi bi-person"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="fw-semibold mb-1">
                                    <a href="{{ route('experts.show', $expert) }}" style="color: var(--color-text); text-decoration: none;">
                                        {{ $expert->name }}
                                    </a>
                                </h6>
                                <small style="color: var(--color-text-secondary);">{{ $expert->title }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- 微信分享 -->
                <div class="lecture-card mb-4">
                    <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--color-bg-warm);">
                        <h5 class="font-serif fw-semibold mb-0" style="font-size: 1.25rem;">
                            <i class="bi bi-share me-2" style="color: var(--color-sage);"></i>
                            分享视频
                        </h5>
                    </div>
                    <div style="padding: 2rem; text-align: center;">
                        <button id="shareBtn" class="btn-parsley btn-parsley-outline" style="border-color: var(--color-border); color: var(--color-text);">
                            <i class="bi bi-wechat me-2" style="color: var(--color-sage);"></i>微信扫码分享
                        </button>
                        <p style="color: var(--color-text-secondary); font-size: 0.85rem; margin-top: 1rem;">点击按钮生成分享二维码</p>
                    </div>
                </div>

                <!-- 相关视频 -->
                @if($relatedVideos->count() > 0)
                <div class="lecture-card">
                    <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--color-bg-warm);">
                        <h5 class="font-serif fw-semibold mb-0" style="font-size: 1.25rem;">
                            <i class="bi bi-play-circle me-2" style="color: var(--color-sage);"></i>
                            相关视频
                        </h5>
                    </div>
                    <div style="padding: 1.5rem 2rem;">
                        @foreach($relatedVideos as $related)
                        <div class="d-flex {{ !$loop->last ? 'mb-3 pb-3' : '' }}"
                             style="{{ !$loop->last ? 'border-bottom: 1px solid var(--color-bg-warm);' : '' }}">
                            <div class="position-relative me-3" style="width: 90px; height: 65px; flex-shrink: 0; border-radius: var(--radius-sm); overflow: hidden;">
                                @if($related->cover_image)
                                    <img src="{{ $related->thumb_cover }}"
                                         style="width: 100%; height: 100%; object-fit: cover;"
                                         alt="{{ $related->title }}">
                                @else
                                    <div class="d-flex align-items-center justify-content-center"
                                         style="width: 100%; height: 100%; background: var(--color-bg-warm);">
                                        <i class="bi bi-play-fill" style="color: var(--color-text-light);"></i>
                                    </div>
                                @endif
                                <div class="position-absolute bottom-0 end-0 m-1">
                                    <span style="background: rgba(0,0,0,0.7); color: #fff; padding: 0.15rem 0.5rem; border-radius: var(--radius-pill); font-size: 0.65rem;">
                                        {{ $related->formatted_duration }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-semibold mb-1" style="font-size: 0.9rem; line-height: 1.3;">
                                    <a href="{{ route('videos.show', $related) }}" style="color: var(--color-text); text-decoration: none;">
                                        {{ Str::limit($related->title, 25) }}
                                    </a>
                                </h6>
                                @if($related->lecture)
                                    <small style="color: var(--color-text-light);">
                                        <i class="bi bi-collection me-1"></i>
                                        {{ Str::limit($related->lecture->title, 15) }}
                                    </small>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- 微信分享遮罩层 -->
<div id="shareModal" class="d-none position-fixed top-0 start-0 w-100 h-100"
     style="background: rgba(0,0,0,0.7); z-index: 9999; backdrop-filter: blur(5px);">
    <div class="position-absolute top-50 start-50 translate-middle text-center"
         style="background: #fff; padding: 3rem; border-radius: var(--radius-lg); min-width: 320px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
        <div class="d-inline-flex align-items-center justify-content-center mb-4"
             style="width: 60px; height: 60px; background: var(--color-bg-warm); border-radius: 50%;">
            <i class="bi bi-wechat" style="font-size: 1.75rem; color: var(--color-sage);"></i>
        </div>
        <h5 class="font-serif fw-semibold mb-3">微信扫码分享</h5>
        <div class="my-4 d-inline-block p-3" style="background: var(--color-bg-warm); border-radius: var(--radius-md);">
            <img src="{{ route('qrcode', ['url' => $shareUrl]) }}" alt="分享二维码" style="width: 200px; height: 200px;">
        </div>
        <p style="color: var(--color-text-secondary); margin-bottom: 1.5rem;">打开微信，扫描二维码分享</p>
        <button class="btn-parsley btn-parsley-outline" style="border-color: var(--color-border); color: var(--color-text);"
                onclick="document.getElementById('shareModal').classList.add('d-none')">
            <i class="bi bi-x-lg me-2"></i>关闭
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/plugins/ckplayer/ckplayer.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 初始化视频播放器（仅在视频文件存在时）
    @if($video->video_url && \Storage::disk('public')->exists($video->video_url))
    if (document.getElementById('video-player')) {
        var videoObject = {
            container: '#video-player',
            variable: 'player',
            autoplay: false,
            video: '{{ Storage::url($video->video_url) }}'
        };
        var player = new ckplayer(videoObject);
    }
    @endif

    // 分享按钮 - 点击显示遮罩层
    document.getElementById('shareBtn')?.addEventListener('click', function() {
        document.getElementById('shareModal').classList.remove('d-none');
    });

    // 点击遮罩层关闭
    document.getElementById('shareModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('d-none');
        }
    });
});
</script>
@endpush
