@extends('layouts.app')

@section('title', '多瑞吉医学名家讲堂')

@section('content')
<!-- 直播预告区域 -->
<section class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-4">多瑞吉医学名家讲堂</h1>
        <p class="lead mb-5">邀请国内外名医、教授开设专题在线讲座，进行相关领域学术及技术普及</p>

        @if($liveLecture)
            <div class="d-inline-block bg-danger text-white px-4 py-2 rounded-pill live-badge">
                <i class="bi bi-broadcast"></i> 正在直播
            </div>
            <h3 class="mt-3">{{ $liveLecture->title }}</h3>
            <a href="{{ route('lectures.show', $liveLecture) }}" class="btn btn-light btn-lg mt-3">
                <i class="bi bi-play-circle"></i> 进入直播间
            </a>
        @elseif($nextLecture)
            <div id="countdown" class="d-inline-block bg-white text-dark px-4 py-3 rounded-3">
                <h5 class="mb-2">距离下一场直播还有</h5>
                <div class="d-flex justify-content-center gap-3">
                    <div>
                        <span id="days" class="display-4 fw-bold text-primary">00</span>
                        <div>天</div>
                    </div>
                    <div>
                        <span id="hours" class="display-4 fw-bold text-primary">00</span>
                        <div>时</div>
                    </div>
                    <div>
                        <span id="minutes" class="display-4 fw-bold text-primary">00</span>
                        <div>分</div>
                    </div>
                    <div>
                        <span id="seconds" class="display-4 fw-bold text-primary">00</span>
                        <div>秒</div>
                    </div>
                </div>
            </div>
            <h3 class="mt-3">{{ $nextLecture->title }}</h3>
            <p>直播时间：{{ $nextLecture->live_start_time->format('Y年m月d日 H:i') }}</p>
        @else
            <div class="d-inline-block bg-secondary text-white px-4 py-2 rounded-pill">
                <i class="bi bi-clock-history"></i> 直播已结束
            </div>
        @endif
    </div>
</section>

<!-- 本期专家 -->
@if($currentExperts->count() > 0)
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">本期专家</h2>
        <div class="row justify-content-center">
            @foreach($currentExperts as $expert)
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="card card-hover text-center h-100">
                    @if($expert->avatar)
                        <img src="{{ Storage::url($expert->avatar) }}" class="card-img-top rounded-circle mx-auto mt-4" style="width: 120px; height: 120px; object-fit: cover;" alt="{{ $expert->name }}">
                    @else
                        <div class="mx-auto mt-4 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                            <i class="bi bi-person display-4 text-muted"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $expert->name }}</h5>
                        <p class="card-text text-muted">{{ $expert->title }}</p>
                        <p class="card-text"><small>{{ $expert->hospital }}</small></p>
                        <a href="{{ route('experts.show', $expert) }}" class="btn btn-outline-primary btn-sm">查看详情</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- 精彩回顾 -->
@if($recentLectures->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">精彩回顾</h2>
        <div class="row">
            @foreach($recentLectures as $lecture)
            <div class="col-md-4 mb-4">
                <div class="card card-hover h-100">
                    @if($lecture->cover_image)
                        <img src="{{ Storage::url($lecture->cover_image) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $lecture->title }}">
                    @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-camera display-4 text-white"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $lecture->title }}</h5>
                        <p class="card-text text-muted">
                            <small>
                                <i class="bi bi-calendar"></i> {{ $lecture->live_start_time ? $lecture->live_start_time->format('Y-m-d') : '-' }}
                            </small>
                        </p>
                        <p class="card-text">{{ Str::limit($lecture->description, 100) }}</p>
                        <a href="{{ route('lectures.show', $lecture) }}" class="btn btn-outline-primary">查看详情</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@push('scripts')
@if(isset($nextLecture) && $nextLecture)
<script>
// 直播倒计时
(function() {
    const targetTime = new Date('{{ $nextLecture->live_start_time->format('Y-m-d\TH:i:s') }}').getTime();

    function updateCountdown() {
        const now = new Date().getTime();
        const diff = targetTime - now;

        if (diff <= 0) {
            document.getElementById('countdown').innerHTML = '<h5>直播即将开始</h5>';
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
</script>
@endif
@endpush
