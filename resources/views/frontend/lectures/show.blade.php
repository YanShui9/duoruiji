@extends('layouts.app')

@section('title', $lecture->title . ' - 多瑞吉医学名家讲堂')
@section('description', Str::limit($lecture->description, 160))

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">首页</a></li>
            <li class="breadcrumb-item"><a href="{{ route('lectures.index') }}">名家讲堂</a></li>
            <li class="breadcrumb-item active">{{ $lecture->title }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <!-- 直播/视频区域 -->
            @if($lecture->status === 1)
                <!-- 正在直播 -->
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white">
                        <i class="bi bi-broadcast"></i> 正在直播
                    </div>
                    <div class="card-body p-0">
                        <div class="ratio ratio-16x9">
                            <iframe src="{{ $lecture->live_url }}" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            @elseif($lecture->status === 0 && $lecture->live_start_time && $lecture->live_start_time->isFuture())
                <!-- 直播预告 -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-clock"></i> 直播预告
                    </div>
                    <div class="card-body text-center py-5">
                        <h4>{{ $lecture->title }}</h4>
                        <p class="text-muted">直播时间：{{ $lecture->live_start_time->format('Y年m月d日 H:i') }}</p>
                        <div id="countdown" class="my-4">
                            <div class="d-flex justify-content-center gap-3">
                                <div class="bg-light rounded p-3">
                                    <span id="days" class="display-6 fw-bold text-primary">00</span>
                                    <div class="text-muted">天</div>
                                </div>
                                <div class="bg-light rounded p-3">
                                    <span id="hours" class="display-6 fw-bold text-primary">00</span>
                                    <div class="text-muted">时</div>
                                </div>
                                <div class="bg-light rounded p-3">
                                    <span id="minutes" class="display-6 fw-bold text-primary">00</span>
                                    <div class="text-muted">分</div>
                                </div>
                                <div class="bg-light rounded p-3">
                                    <span id="seconds" class="display-6 fw-bold text-primary">00</span>
                                    <div class="text-muted">秒</div>
                                </div>
                            </div>
                        </div>

                        <!-- 微信分享按钮 -->
                        <button class="btn btn-outline-success mt-3" id="shareBtn">
                            <i class="bi bi-share"></i> 微信分享
                        </button>
                    </div>
                </div>
            @else
                <!-- 直播已结束 -->
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <i class="bi bi-clock-history"></i> 直播已结束
                    </div>
                    <div class="card-body text-center py-5">
                        <i class="bi bi-check-circle display-1 text-success"></i>
                        <h4 class="mt-3">本次直播已结束</h4>
                        <p class="text-muted">请关注往期视频观看录播内容</p>
                        <a href="{{ route('videos.index') }}" class="btn btn-primary">查看往期视频</a>
                    </div>
                </div>
            @endif

            <!-- 讲座简介 -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">讲座简介</h5>
                </div>
                <div class="card-body">
                    <p>{!! nl2br(e($lecture->description)) !!}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- 本期专家 -->
            @if($experts->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">本期专家</h5>
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
                    <h5 class="mb-0">分享讲座</h5>
                </div>
                <div class="card-body text-center">
                    <div id="qrcode" class="mb-3"></div>
                    <p class="text-muted small">微信扫码分享此讲座</p>
                </div>
            </div>

            <!-- 相关讲座 -->
            @if($relatedLectures->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">相关讲座</h5>
                </div>
                <div class="card-body">
                    @foreach($relatedLectures as $related)
                    <div class="d-flex mb-3">
                        @if($related->cover_image)
                            <img src="{{ Storage::url($related->cover_image) }}" class="rounded me-3" style="width: 80px; height: 60px; object-fit: cover;" alt="{{ $related->title }}">
                        @else
                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 60px;">
                                <i class="bi bi-camera text-muted"></i>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-0"><a href="{{ route('lectures.show', $related) }}">{{ Str::limit($related->title, 30) }}</a></h6>
                            <small class="text-muted">{{ $related->live_start_time ? $related->live_start_time->format('Y-m-d') : '-' }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- 微信分享遮罩层 -->
<div id="shareModal" class="d-none position-fixed top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.7); z-index: 9999;">
    <div class="position-absolute top-50 start-50 translate-middle bg-white p-4 rounded text-center">
        <h5>微信扫码分享</h5>
        <div id="shareQrcode" class="my-3"></div>
        <p class="text-muted">打开微信，扫描二维码分享</p>
        <button class="btn btn-secondary" onclick="document.getElementById('shareModal').classList.add('d-none')">关闭</button>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/plugins/phpqrcode/phpqrcode.min.js') }}"></script>
<script>
// 直播倒计时
@if($lecture->status === 0 && $lecture->live_start_time && $lecture->live_start_time->isFuture())
(function() {
    const targetTime = new Date('{{ $lecture->live_start_time->format('Y-m-d\TH:i:s') }}').getTime();

    function updateCountdown() {
        const now = new Date().getTime();
        const diff = targetTime - now;

        if (diff <= 0) {
            location.reload();
            return;
        }

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        document.getElementById('days').textContent = String(days).padStart(2, '0');
        document.getElementById('hours').textContent = String(hours).padStart(2, '0');
        document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
        document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
})();
@endif

// 生成二维码
@if(isset($lecture))
document.addEventListener('DOMContentLoaded', function() {
    const url = window.location.href;

    // 侧边栏二维码
    if (document.getElementById('qrcode')) {
        new QRCode(document.getElementById('qrcode'), {
            text: url,
            width: 150,
            height: 150
        });
    }

    // 分享按钮
    document.getElementById('shareBtn')?.addEventListener('click', function() {
        document.getElementById('shareModal').classList.remove('d-none');
        if (document.getElementById('shareQrcode').children.length === 0) {
            new QRCode(document.getElementById('shareQrcode'), {
                text: url,
                width: 200,
                height: 200
            });
        }
    });
});
@endif
</script>
@endpush
