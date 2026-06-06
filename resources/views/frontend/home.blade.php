@extends('layouts.app')

@section('title', '多瑞吉医学名家讲堂')

@section('content')
<!-- Hero 区域 - 全屏图片 -->
<section class="hero-section">
    <div class="hero-bg">
        <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=1920&q=80"
             alt="医学讲座"
             loading="eager">
    </div>
    <div class="hero-overlay"></div>
    <div class="container" style="max-width: 1200px;">
        <div class="hero-content">
            @if($liveLectures->count() > 0)
                <!-- 正在直播 - 柔和风格 -->
                <div class="live-badge-wrapper mb-4">
                    <span class="live-badge">
                        <span class="live-dot"></span>
                        <span>正在直播</span>
                        @if($liveLectures->count() > 1)
                            <span class="live-count">{{ $liveLectures->count() }} 场</span>
                        @endif
                    </span>
                </div>

                @if($liveLectures->count() > 1)
                    <!-- 多个直播 - 轮播 -->
                    <div class="live-carousel-wrapper">
                        <div id="liveCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000" data-bs-wrap="true">
                            <div class="carousel-inner">
                                @foreach($liveLectures as $index => $lecture)
                                    @php
                                        $lectureExperts = $lecture->experts();
                                    @endphp
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}"
                                         data-experts='{{ $lectureExperts->map(function($e) { return ["name" => $e->name, "title" => $e->title, "hospital" => $e->hospital, "avatar" => $e->thumb_avatar, "url" => route("experts.show", $e)]; })->toJson() }}'>
                                        <div class="live-carousel-content">
                                            <h1 class="live-carousel-title">{{ $lecture->title }}</h1>
                                            <p class="live-carousel-subtitle">
                                                @if($lectureExperts->count() > 0)
                                                    <i class="bi bi-person-video3 me-2"></i>
                                                    主讲：{{ $lectureExperts->pluck('name')->join('、') }}
                                                @else
                                                    正在直播中，立即加入观看
                                                @endif
                                            </p>
                                            <div class="mt-4">
                                                <a href="{{ route('lectures.show', $lecture) }}" class="btn-parsley btn-parsley-primary btn-live-enter">
                                                    <i class="bi bi-play-circle"></i> 进入直播间
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- 左箭头 -->
                            <button class="carousel-arrow carousel-arrow-prev" type="button" data-bs-target="#liveCarousel" data-bs-slide="prev">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <polyline points="15 18 9 12 15 6"></polyline>
                                </svg>
                            </button>

                            <!-- 右箭头 -->
                            <button class="carousel-arrow carousel-arrow-next" type="button" data-bs-target="#liveCarousel" data-bs-slide="next">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <polyline points="9 6 15 12 9 18"></polyline>
                                </svg>
                            </button>

                            <!-- 指示器 -->
                            <div class="carousel-dots">
                                @foreach($liveLectures as $index => $lecture)
                                    <button type="button" class="carousel-dot {{ $index === 0 ? 'active' : '' }}"
                                            data-bs-target="#liveCarousel" data-bs-slide-to="{{ $index }}"
                                            aria-label="直播 {{ $index + 1 }}">
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <!-- 单个直播 -->
                    <h1 class="fade-in">{{ $liveLecture->title }}</h1>
                    <p class="fade-in">正在直播中，立即加入观看</p>
                    <div class="fade-in">
                        <a href="{{ route('lectures.show', $liveLecture) }}" class="btn-parsley btn-parsley-primary">
                            <i class="bi bi-play-circle"></i> 进入直播间
                        </a>
                    </div>
                @endif

            @elseif($nextLecture)
                <!-- 直播倒计时 -->
                <span class="badge-shadcn mb-4 d-inline-flex align-items-center gap-2"
                      style="font-size: 0.85rem; padding: 0.5rem 1.25rem; background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25);">
                    <i class="bi bi-calendar-event"></i> 即将开播
                </span>
                <h1 class="fade-in">多瑞吉医学名家讲堂</h1>
                <p class="fade-in">邀请国内外名医、教授开设专题在线讲座，进行相关领域学术及技术普及和沟通</p>

                <div class="countdown-card fade-in mb-4">
                    <p style="color: rgba(255,255,255,0.7); font-size: 0.85rem; margin-bottom: 1.25rem;">
                        距离下一场直播还有
                    </p>
                    <div class="d-flex justify-content-center">
                        <div class="countdown-item">
                            <div class="countdown-number" id="days">00</div>
                            <div class="countdown-label">天</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-number" id="hours">00</div>
                            <div class="countdown-label">时</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-number" id="minutes">00</div>
                            <div class="countdown-label">分</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-number" id="seconds">00</div>
                            <div class="countdown-label">秒</div>
                        </div>
                    </div>
                    <div style="margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid rgba(255,255,255,0.15);">
                        <h6 style="color: #fff; font-weight: 600; margin-bottom: 0.25rem;">{{ $nextLecture->title }}</h6>
                        <small style="color: rgba(255,255,255,0.6);">
                            <i class="bi bi-calendar me-1"></i>
                            {{ $nextLecture->live_start_time->format('Y年m月d日 H:i') }}
                        </small>
                    </div>
                </div>

                <div class="fade-in">
                    <a href="{{ route('lectures.show', $nextLecture) }}" class="btn-parsley btn-parsley-outline">
                        查看详情 <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

            @else
                <!-- 默认状态 -->
                <h1 class="fade-in">多瑞吉医学名家讲堂</h1>
                <p class="fade-in">邀请国内外名医、教授开设专题在线讲座，进行相关领域学术及技术普及和沟通</p>
                <div class="fade-in">
                    <a href="{{ route('lectures.index') }}" class="btn-parsley btn-parsley-primary">
                        浏览讲座 <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- 数据统计 - 暖色背景 -->
<section style="padding: 5rem 0; background: var(--color-bg-warm);">
    <div class="container" style="max-width: 1200px;">
        <div class="row g-4">
            <div class="col-md-3 col-6 fade-in">
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['experts'] }}</div>
                    <div class="stat-label">知名专家</div>
                </div>
            </div>
            <div class="col-md-3 col-6 fade-in" style="transition-delay: 100ms;">
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['lectures'] }}</div>
                    <div class="stat-label">精彩讲座</div>
                </div>
            </div>
            <div class="col-md-3 col-6 fade-in" style="transition-delay: 200ms;">
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['videos'] }}</div>
                    <div class="stat-label">录播视频</div>
                </div>
            </div>
            <div class="col-md-3 col-6 fade-in" style="transition-delay: 300ms;">
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['live_now'] }}</div>
                    <div class="stat-label">正在直播</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 本期专家 - 仅在有直播时显示 -->
@if(($liveLecture || $nextLecture) && $currentExperts->count() > 0)
<section style="padding: var(--section-padding) 0;" id="experts-section">
    <div class="container" style="max-width: 1200px;">
        <div class="section-header text-center fade-in">
            <div class="section-label">OUR EXPERTS</div>
            <h2 id="experts-title">{{ $liveLecture ? '本期专家' : '本期嘉宾' }}</h2>
            <p>汇聚国内顶尖医学专家，分享前沿学术成果与临床经验</p>
        </div>
        <div class="row g-4 justify-content-center" id="experts-grid">
            @foreach($currentExperts as $index => $expert)
            <div class="col-lg-3 col-md-6 fade-in" style="transition-delay: {{ $index * 100 }}ms;">
                <div class="expert-card">
                    <div class="expert-avatar-wrapper">
                        @if($expert->avatar)
                            <img src="{{ $expert->thumb_avatar }}" alt="{{ $expert->name }}">
                        @else
                            <div class="expert-avatar-placeholder">
                                <i class="bi bi-person"></i>
                            </div>
                        @endif
                    </div>
                    <div class="expert-name">{{ $expert->name }}</div>
                    <div class="expert-title">{{ $expert->title }}</div>
                    <div class="expert-hospital">
                        <i class="bi bi-hospital me-1"></i>
                        {{ $expert->hospital }}
                    </div>
                    <a href="{{ route('experts.show', $expert) }}" class="btn-parsley btn-parsley-ghost mt-3">
                        查看详情 <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-5 fade-in">
            <a href="{{ route('experts.index') }}" class="btn-parsley btn-parsley-dark">
                查看全部专家 <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- 平台特色 - 图文交替 -->
<section style="padding: var(--section-padding) 0; background: var(--color-bg-cream);">
    <div class="container" style="max-width: 1200px;">
        <div class="section-header text-center fade-in">
            <div class="section-label">WHY CHOOSE US</div>
            <h2>平台特色</h2>
            <p>专业、权威、便捷的医学学术交流平台</p>
        </div>

        <!-- 特色1 -->
        <div class="feature-block">
            <div class="feature-block-img fade-in-left">
                <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=800&q=80"
                     alt="权威专家">
            </div>
            <div class="feature-block-content fade-in-right">
                <div class="feature-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div class="feature-title">权威专家团队</div>
                <div class="feature-description">
                    汇聚国内外知名医学专家，分享最新研究成果和临床经验。每位专家均经过严格筛选，确保学术权威性和专业性。
                </div>
                <a href="{{ route('experts.index') }}" class="btn-parsley btn-parsley-ghost">
                    了解专家团队 <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- 特色2 -->
        <div class="feature-block">
            <div class="feature-block-img fade-in-right">
                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&q=80"
                     alt="在线直播">
            </div>
            <div class="feature-block-content fade-in-left">
                <div class="feature-icon">
                    <i class="bi bi-camera-video"></i>
                </div>
                <div class="feature-title">高清在线直播</div>
                <div class="feature-description">
                    高清流畅的直播体验，实时互动答疑解惑。支持多终端观看，随时随地参与学术交流。
                </div>
                <a href="{{ route('lectures.index') }}" class="btn-parsley btn-parsley-ghost">
                    查看直播安排 <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- 特色3 -->
        <div class="feature-block">
            <div class="feature-block-img fade-in-left">
                <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=800&q=80"
                     alt="随时回看">
            </div>
            <div class="feature-block-content fade-in-right">
                <div class="feature-icon">
                    <i class="bi bi-play-circle"></i>
                </div>
                <div class="feature-title">往期视频回看</div>
                <div class="feature-description">
                    往期视频随时回看，不错过任何精彩内容。支持倍速播放、笔记功能，让学习更高效。
                </div>
                <a href="{{ route('videos.index') }}" class="btn-parsley btn-parsley-ghost">
                    浏览往期视频 <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- 精彩回顾 -->
@if($recentLectures->count() > 0)
<section style="padding: var(--section-padding) 0;">
    <div class="container" style="max-width: 1200px;">
        <div class="section-header text-center fade-in">
            <div class="section-label">RECENT LECTURES</div>
            <h2>精彩回顾</h2>
            <p>回顾往期精彩讲座，学习医学前沿知识</p>
        </div>
        <div class="row g-4">
            @foreach($recentLectures as $index => $lecture)
            <div class="col-lg-4 col-md-6 fade-in" style="transition-delay: {{ $index * 100 }}ms;">
                <div class="lecture-card h-100">
                    <div class="lecture-card-img">
                        @if($lecture->cover_image)
                            <img src="{{ $lecture->thumb_cover }}" alt="{{ $lecture->title }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=600&q=80"
                                 alt="{{ $lecture->title }}">
                        @endif
                        <span class="badge-status badge-ended">
                            <i class="bi bi-check-circle me-1"></i> 已结束
                        </span>
                    </div>
                    <div class="lecture-card-body">
                        <div class="lecture-card-meta">
                            <i class="bi bi-calendar me-1"></i>
                            {{ $lecture->live_start_time ? $lecture->live_start_time->format('Y年m月d日') : '-' }}
                        </div>
                        <div class="lecture-card-title">{{ $lecture->title }}</div>
                        <div class="lecture-card-desc">
                            {{ Str::limit(strip_tags($lecture->description), 80) }}
                        </div>
                        <a href="{{ route('lectures.show', $lecture) }}" class="btn-parsley btn-parsley-ghost">
                            观看回顾 <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-5 fade-in">
            <a href="{{ route('lectures.index') }}" class="btn-parsley btn-parsley-dark">
                查看全部讲座 <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- CTA 区域 -->
<section class="cta-section">
    <div class="container" style="max-width: 700px;">
        <h2 class="fade-in">加入我们，共同进步</h2>
        <p class="fade-in">
            与国内外顶尖医学专家面对面交流，获取最新的学术资讯和临床经验
        </p>
        <div class="fade-in">
            <a href="{{ route('lectures.index') }}" class="btn-parsley btn-parsley-primary"
               style="background: var(--color-accent);">
                立即开始学习 <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
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

@if(isset($liveLectures) && $liveLectures->count() > 1)
<script>
// 直播轮播 - 切换时更新专家信息
document.addEventListener('DOMContentLoaded', function() {
    var carousel = document.getElementById('liveCarousel');
    if (!carousel) return;

    var expertsGrid = document.getElementById('experts-grid');
    if (!expertsGrid) return;

    // 监听轮播切换事件 - 同步更新专家信息
    carousel.addEventListener('slide.bs.carousel', function(event) {
        var activeItem = event.relatedTarget;
        var expertsData = activeItem.getAttribute('data-experts');

        if (expertsData) {
            var experts = JSON.parse(expertsData);
            updateExpertsGrid(experts);
        }
    });

    function updateExpertsGrid(experts) {
        if (experts.length === 0) {
            expertsGrid.innerHTML = '<div class="col-12 text-center"><p style="color: var(--color-text-secondary);">暂无专家信息</p></div>';
            return;
        }

        var html = '';
        experts.forEach(function(expert, index) {
            html += '<div class="col-lg-3 col-md-6">';
            html += '  <div class="expert-card">';
            html += '    <div class="expert-avatar-wrapper">';
            if (expert.avatar) {
                html += '      <img src="' + expert.avatar + '" alt="' + expert.name + '">';
            } else {
                html += '      <div class="expert-avatar-placeholder"><i class="bi bi-person"></i></div>';
            }
            html += '    </div>';
            html += '    <div class="expert-name">' + expert.name + '</div>';
            html += '    <div class="expert-title">' + expert.title + '</div>';
            html += '    <div class="expert-hospital"><i class="bi bi-hospital me-1"></i>' + expert.hospital + '</div>';
            html += '    <a href="' + expert.url + '" class="btn-parsley btn-parsley-ghost mt-3">查看详情 <i class="bi bi-arrow-right"></i></a>';
            html += '  </div>';
            html += '</div>';
        });

        // 直接更新，无延迟
        expertsGrid.innerHTML = html;
    }

    // 更新轮播指示器状态 - 切换开始时同步更新
    carousel.addEventListener('slide.bs.carousel', function(event) {
        var dots = carousel.querySelectorAll('.carousel-dot');
        dots.forEach(function(dot, index) {
            if (index === event.to) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
    });
});
</script>
@endif
@endpush
