@extends('layouts.app')

@section('title', $lecture->title . ' - 多瑞吉医学名家讲堂')
@section('description', Str::limit($lecture->description, 160))

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
                        <a href="{{ route('lectures.index') }}" style="color: var(--color-text-secondary); text-decoration: none; font-size: 0.9rem;">名家讲堂</a>
                    </li>
                    <li class="breadcrumb-item active" style="color: var(--color-text); font-size: 0.9rem;">{{ Str::limit($lecture->title, 30) }}</li>
                </ol>
            </nav>
            <a href="{{ route('lectures.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> 返回
            </a>
        </div>
    </div>
</section>

<div style="padding: var(--section-padding) 0;">
    <div class="container" style="max-width: 1200px;">
        <div class="row g-4">
            <div class="col-lg-8">
                <!-- 直播/视频区域 -->
                @if($lecture->status === 1)
                    <!-- 正在直播 -->
                    <div class="lecture-card mb-4 overflow-hidden">
                        <div class="d-flex align-items-center gap-2 px-4 py-3" style="background: var(--color-primary); color: white;">
                            <span class="live-badge" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);">
                                <span class="live-dot"></span>
                                <span>直播中</span>
                            </span>
                            <span class="ms-2 fw-medium">{{ $lecture->title }}</span>
                        </div>
                        <div class="text-center" style="padding: 4rem 2rem;">
                            <div class="d-inline-flex align-items-center justify-content-center mb-4"
                                 style="width: 80px; height: 80px; background: rgba(26, 58, 42, 0.08); border-radius: 50%;">
                                <i class="bi bi-broadcast" style="font-size: 2.5rem; color: var(--color-primary);"></i>
                            </div>
                            <h3 class="font-serif fw-semibold mb-3">直播正在进行中</h3>
                            <p style="color: var(--color-text-secondary); margin-bottom: 2rem;">
                                输入您的姓名后即可进入直播间观看
                            </p>
                            <button class="btn-parsley btn-parsley-primary" id="joinLiveBtn"
                                    style="padding: 1rem 3rem;">
                                <i class="bi bi-play-circle me-2"></i> 进入直播间
                            </button>
                        </div>
                    </div>

                    <!-- 姓名输入弹窗 -->
                    <div id="liveModal" class="d-none position-fixed top-0 start-0 w-100 h-100"
                         style="background: rgba(0,0,0,0.7); z-index: 9999; backdrop-filter: blur(5px);">
                        <div class="position-absolute top-50 start-50 translate-middle text-center"
                             style="background: #fff; padding: 3rem; border-radius: var(--radius-lg); min-width: 380px; max-width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                            <div class="d-inline-flex align-items-center justify-content-center mb-4"
                                 style="width: 60px; height: 60px; background: rgba(26, 58, 42, 0.08); border-radius: 50%;">
                                <i class="bi bi-broadcast" style="font-size: 1.75rem; color: var(--color-primary);"></i>
                            </div>
                            <h5 class="font-serif fw-semibold mb-2">进入直播间</h5>
                            <p style="color: var(--color-text-secondary); margin-bottom: 1.5rem; font-size: 0.95rem;">
                                请输入您的姓名，方便在直播间互动
                            </p>
                            <div class="mb-4">
                                <input type="text" id="viewerName" class="form-control"
                                       style="padding: 0.75rem 1rem; border-radius: var(--radius-sm); font-size: 1rem;"
                                       placeholder="请输入您的姓名" maxlength="20" autofocus>
                                <div id="nameError" class="d-none mt-2" style="color: var(--color-danger); font-size: 0.85rem;">
                                    请输入姓名后再进入直播间
                                </div>
                            </div>
                            <div class="d-flex gap-3 justify-content-center">
                                <button class="btn-parsley btn-parsley-outline"
                                        style="border-color: var(--color-border); color: var(--color-text); padding: 0.75rem 2rem;"
                                        onclick="document.getElementById('liveModal').classList.add('d-none')">
                                    取消
                                </button>
                                <button class="btn-parsley btn-parsley-primary"
                                        style="padding: 0.75rem 2rem;"
                                        id="confirmJoinBtn">
                                    <i class="bi bi-box-arrow-up-right me-2"></i> 确认进入
                                </button>
                            </div>
                        </div>
                    </div>

                @elseif($lecture->status === 0 && $lecture->live_start_time && $lecture->live_start_time->isFuture())
                    <!-- 直播预告 -->
                    <div class="lecture-card mb-4 text-center" style="padding: 4rem 2rem;">
                        <span class="badge-shadcn badge-upcoming mb-4 d-inline-flex align-items-center gap-2">
                            <i class="bi bi-clock"></i> 直播预告
                        </span>
                        <h2 class="font-serif mb-3" style="font-size: 2rem; font-weight: 600;">{{ $lecture->title }}</h2>
                        <p style="color: var(--color-text-secondary); margin-bottom: 2rem;">
                            <i class="bi bi-calendar me-2"></i>
                            直播时间：{{ $lecture->live_start_time->format('Y年m月d日 H:i') }}
                            @if($lecture->live_end_time)
                                @if($lecture->live_start_time->format('Y-m-d') === $lecture->live_end_time->format('Y-m-d'))
                                    至 {{ $lecture->live_end_time->format('H:i') }}
                                @else
                                    至 {{ $lecture->live_end_time->format('Y年m月d日 H:i') }}
                                @endif
                            @endif
                        </p>

                        <!-- 倒计时 -->
                        <div id="countdown" style="background: var(--color-bg-warm); border-radius: var(--radius-lg); padding: 2rem; display: inline-block; margin-bottom: 2rem;">
                            <div class="d-flex justify-content-center">
                                <div class="countdown-item px-4">
                                    <div class="countdown-number" id="days" style="color: var(--color-primary);">00</div>
                                    <div class="countdown-label" style="color: var(--color-text-secondary);">天</div>
                                </div>
                                <div class="countdown-item px-4">
                                    <div class="countdown-number" id="hours" style="color: var(--color-primary);">00</div>
                                    <div class="countdown-label" style="color: var(--color-text-secondary);">时</div>
                                </div>
                                <div class="countdown-item px-4">
                                    <div class="countdown-number" id="minutes" style="color: var(--color-primary);">00</div>
                                    <div class="countdown-label" style="color: var(--color-text-secondary);">分</div>
                                </div>
                                <div class="countdown-item px-4">
                                    <div class="countdown-number" id="seconds" style="color: var(--color-primary);">00</div>
                                    <div class="countdown-label" style="color: var(--color-text-secondary);">秒</div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <button class="btn-parsley btn-parsley-outline" id="shareBtn" style="border-color: var(--color-border); color: var(--color-text);">
                                <i class="bi bi-share"></i> 微信分享
                            </button>
                        </div>
                    </div>

                @else
                    <!-- 直播已结束 -->
                    <div class="lecture-card mb-4 text-center" style="padding: 4rem 2rem;">
                        <div class="d-inline-flex align-items-center justify-content-center mb-4"
                             style="width: 80px; height: 80px; background: var(--color-bg-warm); border-radius: 50%;">
                            <i class="bi bi-check-circle" style="font-size: 2.5rem; color: var(--color-sage);"></i>
                        </div>
                        <h2 class="font-serif mb-3" style="font-size: 2rem; font-weight: 600;">本次直播已结束</h2>
                        <p style="color: var(--color-text-secondary); margin-bottom: 2rem;">请关注往期视频观看录播内容</p>
                        <a href="{{ route('videos.index', ['lecture_id' => $lecture->id]) }}" class="btn-parsley btn-parsley-dark">
                            <i class="bi bi-play-circle"></i> 查看往期视频
                        </a>
                    </div>
                @endif

                <!-- 讲座简介 -->
                <div class="lecture-card">
                    <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--color-bg-warm);">
                        <h5 class="font-serif fw-semibold mb-0" style="font-size: 1.25rem;">
                            <i class="bi bi-file-text me-2" style="color: var(--color-sage);"></i>
                            讲座简介
                        </h5>
                    </div>
                    <div style="padding: 2rem;">
                        <div style="line-height: 1.9; color: var(--color-text-secondary); font-size: 1.05rem;">
                            {!! preg_replace('/<(\w+)\s[^>]*>/', '<$1>', strip_tags($lecture->description, '<p><br><strong><em><b><i><ul><ol><li><h1><h2><h3><h4><h5><h6>')) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- 本期专家 -->
                @if($experts->count() > 0)
                <div class="lecture-card mb-4">
                    <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--color-bg-warm);">
                        <h5 class="font-serif fw-semibold mb-0" style="font-size: 1.25rem;">
                            <i class="bi bi-people me-2" style="color: var(--color-sage);"></i>
                            本期专家
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
                            分享讲座
                        </h5>
                    </div>
                    <div style="padding: 2rem; text-align: center;">
                        <button id="shareBtnSide" class="btn-parsley btn-parsley-outline" style="border-color: var(--color-border); color: var(--color-text);">
                            <i class="bi bi-wechat me-2" style="color: var(--color-sage);"></i>微信扫码分享
                        </button>
                        <p style="color: var(--color-text-secondary); font-size: 0.85rem; margin-top: 1rem;">点击按钮生成分享二维码</p>
                    </div>
                </div>

                <!-- 相关讲座 -->
                @if($relatedLectures->count() > 0)
                <div class="lecture-card">
                    <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--color-bg-warm);">
                        <h5 class="font-serif fw-semibold mb-0" style="font-size: 1.25rem;">
                            <i class="bi bi-collection me-2" style="color: var(--color-sage);"></i>
                            相关讲座
                        </h5>
                    </div>
                    <div style="padding: 1.5rem 2rem;">
                        @foreach($relatedLectures as $related)
                        <div class="d-flex {{ !$loop->last ? 'mb-3 pb-3' : '' }}"
                             style="{{ !$loop->last ? 'border-bottom: 1px solid var(--color-bg-warm);' : '' }}">
                            @if($related->cover_image)
                                <img src="{{ $related->thumb_cover }}" class="rounded-3 me-3"
                                     style="width: 80px; height: 60px; object-fit: cover;"
                                     alt="{{ $related->title }}">
                            @else
                                <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                                     style="width: 80px; height: 60px; background: var(--color-bg-warm);">
                                    <i class="bi bi-camera" style="color: var(--color-text-light);"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="fw-semibold mb-1" style="font-size: 0.9rem;">
                                    <a href="{{ route('lectures.show', $related) }}" style="color: var(--color-text); text-decoration: none;">
                                        {{ Str::limit($related->title, 25) }}
                                    </a>
                                </h6>
                                <small style="color: var(--color-text-light);">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $related->live_start_time ? $related->live_start_time->format('Y-m-d') : '-' }}
                                </small>
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
<script>
</script>
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

// 分享按钮功能
@if(isset($lecture))
document.addEventListener('DOMContentLoaded', function() {
    // 分享按钮 - 点击显示遮罩层
    document.getElementById('shareBtn')?.addEventListener('click', function() {
        document.getElementById('shareModal').classList.remove('d-none');
    });
    document.getElementById('shareBtnSide')?.addEventListener('click', function() {
        document.getElementById('shareModal').classList.remove('d-none');
    });

    // 点击遮罩层关闭
    document.getElementById('shareModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('d-none');
        }
    });
});
@endif

// 直播间加入功能
@if($lecture->status === 1)
document.addEventListener('DOMContentLoaded', function() {
    var joinBtn = document.getElementById('joinLiveBtn');
    var liveModal = document.getElementById('liveModal');
    var confirmBtn = document.getElementById('confirmJoinBtn');
    var nameInput = document.getElementById('viewerName');
    var nameError = document.getElementById('nameError');
    var liveUrl = @json($lecture->live_url);

    // 打开弹窗
    joinBtn.addEventListener('click', function() {
        liveModal.classList.remove('d-none');
        nameInput.focus();
        // 读取本地存储的姓名
        var savedName = localStorage.getItem('viewer_name');
        if (savedName) {
            nameInput.value = savedName;
        }
    });

    // 确认进入
    function joinLive() {
        var name = nameInput.value.trim();
        if (!name) {
            nameError.classList.remove('d-none');
            nameInput.focus();
            return;
        }
        nameError.classList.add('d-none');
        // 保存姓名到本地存储
        localStorage.setItem('viewer_name', name);
        // 拼接参数跳转
        var separator = liveUrl.indexOf('?') !== -1 ? '&' : '?';
        var targetUrl = liveUrl + separator + 'viewer=' + encodeURIComponent(name);
        window.open(targetUrl, '_blank');
        liveModal.classList.add('d-none');
    }

    confirmBtn.addEventListener('click', joinLive);
    nameInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') joinLive();
    });
    nameInput.addEventListener('input', function() {
        if (nameInput.value.trim()) {
            nameError.classList.add('d-none');
        }
    });

    // 点击遮罩关闭
    liveModal.addEventListener('click', function(e) {
        if (e.target === liveModal) {
            liveModal.classList.add('d-none');
        }
    });
});
@endif
</script>
@endpush
