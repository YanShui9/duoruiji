@extends('layouts.app')

@section('title', '名家讲堂 - 多瑞吉医学名家讲堂')

@section('content')
<!-- 页面头部 - 全屏图片 -->
<section class="hero-section" style="min-height: 50vh;">
    <div class="hero-bg">
        <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=1920&q=80"
             alt="名家讲堂"
             loading="eager">
    </div>
    <div class="hero-overlay" style="background: linear-gradient(135deg, rgba(26, 58, 42, 0.9) 0%, rgba(26, 58, 42, 0.7) 100%);"></div>
    <div class="container" style="max-width: 1200px;">
        <div class="hero-content text-center" style="max-width: 100%;">
            <div class="section-label mb-3" style="color: rgba(255,255,255,0.7);">LECTURES</div>
            <h1 style="font-size: 3.5rem;">名家讲堂</h1>
            <p style="font-size: 1.25rem; opacity: 0.9; max-width: 500px; margin: 0 auto;">
                汇聚医学名家智慧，分享前沿学术成果
            </p>
            <a href="{{ route('home') }}" class="btn-back-hero mt-3">
                <i class="bi bi-arrow-left"></i> 返回首页
            </a>
        </div>
    </div>
</section>

<!-- 筛选栏 -->
<section style="padding: 2rem 0 0;">
    <div class="container" style="max-width: 1200px;">
        <form action="{{ route('lectures.index') }}" method="GET">
            <div style="background: var(--color-bg-warm); border-radius: var(--radius-md); padding: 1.5rem;">
                <div class="d-flex gap-3 flex-wrap align-items-center">
                    <div style="flex: 1; min-width: 200px; position: relative;">
                        <i class="bi bi-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--color-text-light);"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="搜索讲座标题..."
                               style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 1px solid var(--color-border); border-radius: var(--radius-pill); background: white;">
                    </div>
                    <select name="status" style="padding: 0.75rem 1rem; border: 1px solid var(--color-border); border-radius: var(--radius-pill); background: white; min-width: 120px;">
                        <option value="">全部状态</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>直播中</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>即将开始</option>
                        <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>已结束</option>
                    </select>
                    <button type="submit" class="btn-parsley btn-parsley-dark">
                        <i class="bi bi-search"></i> 搜索
                    </button>
                    <a href="{{ route('lectures.index') }}" class="btn-parsley btn-parsley-outline" style="border-color: var(--color-border); color: var(--color-text-secondary);">
                        重置
                    </a>
                </div>
            </div>
        </form>
        <!-- 分类标签 -->
        <div class="d-flex flex-wrap gap-2 justify-content-center mt-4">
            <a href="{{ route('lectures.index', array_merge(request()->except('category'))) }}"
               class="btn {{ !$currentCategory ? 'btn-parsley btn-parsley-dark' : 'btn-parsley btn-parsley-outline' }}"
               style="{{ $currentCategory ? 'border-color: var(--color-border); color: var(--color-text);' : '' }}">
                全部
            </a>
            @foreach($categories as $key => $name)
                <a href="{{ route('lectures.index', array_merge(request()->except('category'), ['category' => $key])) }}"
                   class="btn {{ $currentCategory === $key ? 'btn-parsley btn-parsley-dark' : 'btn-parsley btn-parsley-outline' }}"
                   style="{{ $currentCategory !== $key ? 'border-color: var(--color-border); color: var(--color-text);' : '' }}">
                    {{ $name }}
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- 讲座列表 -->
<section style="padding: 3rem 0;">
    <div class="container" style="max-width: 1200px;">
        @if($currentCategory)
            <div class="mb-4 text-center">
                <span style="color: var(--color-text-secondary); font-size: 0.95rem;">
                    当前分类：<strong style="color: var(--color-primary);">{{ $categories[$currentCategory] }}</strong>
                </span>
            </div>
        @endif
        <div class="row g-4">
            @forelse($lectures as $index => $lecture)
            <div class="col-lg-4 col-md-6 fade-in" style="transition-delay: {{ ($index % 3) * 100 }}ms;">
                <div class="lecture-card h-100">
                    <div class="lecture-card-img">
                        @if($lecture->cover_image)
                            <img src="{{ $lecture->thumb_cover }}" alt="{{ $lecture->title }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=600&q=80"
                                 alt="{{ $lecture->title }}">
                        @endif

                        <!-- 状态徽章 -->
                        @if($lecture->status === 1)
                            <span class="badge-status badge-live">
                                <i class="bi bi-broadcast me-1"></i> 直播中
                            </span>
                        @elseif($lecture->status === 0 && $lecture->live_start_time && $lecture->live_start_time->isFuture())
                            <span class="badge-status badge-upcoming">
                                <i class="bi bi-clock me-1"></i> 即将开始
                            </span>
                        @else
                            <span class="badge-status badge-ended">
                                <i class="bi bi-check-circle me-1"></i> 已结束
                            </span>
                        @endif

                        <!-- 时间 -->
                        @if($lecture->live_start_time)
                        <div style="position: absolute; bottom: 1rem; right: 1rem;">
                            <span style="background: rgba(0,0,0,0.7); color: #fff; padding: 0.4rem 1rem; border-radius: var(--radius-pill); font-size: 0.8rem; backdrop-filter: blur(10px);">
                                <i class="bi bi-calendar me-1"></i>
                                @if($lecture->live_end_time && $lecture->live_start_time->format('Y-m-d') !== $lecture->live_end_time->format('Y-m-d'))
                                    {{ $lecture->live_start_time->format('m月d日 H:i') }} - {{ $lecture->live_end_time->format('m月d日 H:i') }}
                                @else
                                    {{ $lecture->live_start_time->format('m月d日 H:i') }}
                                    @if($lecture->live_end_time)
                                        - {{ $lecture->live_end_time->format('H:i') }}
                                    @endif
                                @endif
                            </span>
                        </div>
                        @endif
                    </div>

                    <div class="lecture-card-body">
                        <!-- 分类标签 -->
                        @if($lecture->category)
                        <div class="mb-2">
                            <span style="background: var(--color-bg-warm); color: var(--color-sage); padding: 0.3rem 0.8rem; border-radius: var(--radius-pill); font-size: 0.75rem; font-weight: 600;">
                                <i class="bi bi-tag me-1"></i>{{ $lecture->category_name }}
                            </span>
                        </div>
                        @endif

                        <!-- 关联专家 -->
                        @php
                            $experts = $lecture->experts();
                        @endphp
                        @if($experts->count() > 0)
                        <div class="d-flex align-items-center mb-3">
                            <div class="d-flex" style="margin-right: 10px;">
                                @foreach($experts->take(3) as $expert)
                                    @if($expert->avatar)
                                        <img src="{{ $expert->thumb_avatar }}" class="rounded-circle"
                                             style="width: 32px; height: 32px; object-fit: cover; border: 2px solid #fff; margin-left: -8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"
                                             alt="{{ $expert->name }}">
                                    @else
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 32px; height: 32px; background: var(--color-sage); color: #fff; margin-left: -8px; border: 2px solid #fff; font-size: 0.7rem; font-weight: 600;">
                                            {{ mb_substr($expert->name, 0, 1) }}
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <small style="color: var(--color-text-secondary); font-size: 0.85rem;">
                                {{ $experts->first()->name }}{{ $experts->count() > 1 ? ' 等' : '' }}
                            </small>
                        </div>
                        @endif

                        <div class="lecture-card-title">{{ $lecture->title }}</div>
                        <div class="lecture-card-desc">
                            {{ Str::limit(strip_tags($lecture->description), 100) }}
                        </div>

                        <a href="{{ route('lectures.show', $lecture) }}" class="btn-parsley btn-parsley-dark w-100">
                            @if($lecture->status === 1)
                                <i class="bi bi-play-circle"></i> 进入直播间
                            @elseif($lecture->status === 0 && $lecture->live_start_time && $lecture->live_start_time->isFuture())
                                查看详情 <i class="bi bi-arrow-right"></i>
                            @else
                                <i class="bi bi-play-circle"></i> 观看回顾
                            @endif
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="empty-state" style="padding: 6rem 2rem;">
                    <i class="bi bi-camera-video d-block" style="font-size: 3rem; color: var(--color-sage-light); margin-bottom: 1.5rem;"></i>
                    <p style="color: var(--color-text-secondary); font-size: 1.1rem;">暂无讲座信息</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- 分页 -->
        @if($lectures->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $lectures->links() }}
        </div>
        @endif
    </div>
</section>
@endsection
