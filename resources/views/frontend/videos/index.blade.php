@extends('layouts.app')

@section('title', '往期视频 - 多瑞吉医学名家讲堂')

@section('content')
<!-- 页面头部 - 全屏图片 -->
<section class="hero-section" style="min-height: 50vh;">
    <div class="hero-bg">
        <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=1920&q=80"
             alt="往期视频"
             loading="eager">
    </div>
    <div class="hero-overlay" style="background: linear-gradient(135deg, rgba(26, 58, 42, 0.9) 0%, rgba(26, 58, 42, 0.7) 100%);"></div>
    <div class="container" style="max-width: 1200px;">
        <div class="hero-content text-center" style="max-width: 100%;">
            <div class="section-label mb-3" style="color: rgba(255,255,255,0.7);">VIDEO LIBRARY</div>
            <h1 style="font-size: 3.5rem;">往期视频</h1>
            <p style="font-size: 1.25rem; opacity: 0.9; max-width: 500px; margin: 0 auto;">
                回顾精彩讲座，学习医学前沿知识
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
        <form action="{{ route('videos.index') }}" method="GET">
            @if($currentCategory)
                <input type="hidden" name="category" value="{{ $currentCategory }}">
            @endif
            <div style="background: var(--color-bg-warm); border-radius: var(--radius-md); padding: 1.5rem;">
                <div class="d-flex gap-3 flex-wrap align-items-center">
                    <div style="flex: 1; min-width: 200px; position: relative;">
                        <i class="bi bi-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--color-text-light);"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="搜索视频标题..."
                               style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 1px solid var(--color-border); border-radius: var(--radius-pill); background: white;">
                    </div>
                    <select name="expert_id" style="padding: 0.75rem 1rem; border: 1px solid var(--color-border); border-radius: var(--radius-pill); background: white; min-width: 150px;">
                        <option value="">全部专家</option>
                        @foreach($experts as $expert)
                            <option value="{{ $expert->id }}" {{ request('expert_id') == $expert->id ? 'selected' : '' }}>{{ $expert->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-parsley btn-parsley-dark">
                        <i class="bi bi-search"></i> 搜索
                    </button>
                    <a href="{{ route('videos.index') }}" class="btn-parsley btn-parsley-outline" style="border-color: var(--color-border); color: var(--color-text-secondary);">
                        重置
                    </a>
                </div>
            </div>
        </form>
        <!-- 分类标签 -->
        <div class="d-flex flex-wrap gap-2 justify-content-center mt-4">
            <a href="{{ route('videos.index', array_merge(request()->except('category'))) }}"
               class="btn {{ !$currentCategory ? 'btn-parsley btn-parsley-dark' : 'btn-parsley btn-parsley-outline' }}"
               style="{{ $currentCategory ? 'border-color: var(--color-border); color: var(--color-text);' : '' }}">
                全部
            </a>
            @foreach($categories as $key => $name)
                <a href="{{ route('videos.index', array_merge(request()->except('category'), ['category' => $key])) }}"
                   class="btn {{ $currentCategory === $key ? 'btn-parsley btn-parsley-dark' : 'btn-parsley btn-parsley-outline' }}"
                   style="{{ $currentCategory !== $key ? 'border-color: var(--color-border); color: var(--color-text);' : '' }}">
                    {{ $name }}
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- 视频列表 -->
<section style="padding: var(--section-padding) 0;">
    <div class="container" style="max-width: 1200px;">
        <div class="row g-4">
            @forelse($videos as $index => $video)
            <div class="col-lg-4 col-md-6 fade-in" style="transition-delay: {{ ($index % 3) * 100 }}ms;">
                <div class="lecture-card h-100">
                    <div class="lecture-card-img">
                        @if($video->cover_image)
                            <img src="{{ $video->thumb_cover }}" alt="{{ $video->title }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=600&q=80"
                                 alt="{{ $video->title }}">
                        @endif

                        <!-- 时长 -->
                        <div style="position: absolute; bottom: 1rem; right: 1rem;">
                            <span style="background: rgba(0,0,0,0.7); color: #fff; padding: 0.4rem 1rem; border-radius: var(--radius-pill); font-size: 0.8rem; backdrop-filter: blur(10px);">
                                <i class="bi bi-clock me-1"></i>
                                {{ $video->formatted_duration }}
                            </span>
                        </div>

                        <!-- 播放按钮悬浮效果 -->
                        <div class="play-overlay">
                            <div class="play-btn">
                                <i class="bi bi-play-fill"></i>
                            </div>
                        </div>
                    </div>

                    <div class="lecture-card-body">
                        <!-- 所属讲座 -->
                        @if($video->lecture)
                        <div class="mb-3">
                            <span style="background: var(--color-bg-warm); color: var(--color-sage); padding: 0.3rem 0.8rem; border-radius: var(--radius-pill); font-size: 0.75rem; font-weight: 600;">
                                <i class="bi bi-collection me-1"></i>
                                {{ Str::limit($video->lecture->title, 20) }}
                            </span>
                        </div>
                        @endif

                        <!-- 关联专家 -->
                        @php
                            $videoExperts = collect();
                            if ($video->expert_ids) {
                                foreach ($video->expert_ids as $eid) {
                                    if (isset($expertsMap[$eid])) {
                                        $videoExperts->push($expertsMap[$eid]);
                                    }
                                }
                            }
                        @endphp
                        @if($videoExperts->count() > 0)
                        <div class="d-flex align-items-center mb-3">
                            <div class="d-flex" style="margin-right: 10px;">
                                @foreach($videoExperts->take(3) as $expert)
                                    @if($expert->avatar)
                                        <img src="{{ $expert->thumb_avatar }}" class="rounded-circle"
                                             style="width: 28px; height: 28px; object-fit: cover; border: 2px solid #fff; margin-left: -6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"
                                             alt="{{ $expert->name }}">
                                    @else
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 28px; height: 28px; background: var(--color-sage); color: #fff; margin-left: -6px; border: 2px solid #fff; font-size: 0.6rem; font-weight: 600;">
                                            {{ mb_substr($expert->name, 0, 1) }}
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <small style="color: var(--color-text-secondary); font-size: 0.8rem;">
                                {{ $videoExperts->first()->name }}{{ $videoExperts->count() > 1 ? ' 等' : '' }}
                            </small>
                        </div>
                        @endif

                        <div class="lecture-card-title">{{ $video->title }}</div>
                        <div class="lecture-card-desc">
                            {{ Str::limit(strip_tags($video->description), 80) }}
                        </div>

                        <a href="{{ route('videos.show', $video) }}" class="btn-parsley btn-parsley-dark w-100">
                            <i class="bi bi-play-circle"></i> 观看视频
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="empty-state" style="padding: 6rem 2rem;">
                    <i class="bi bi-camera-video d-block" style="font-size: 3rem; color: var(--color-sage-light); margin-bottom: 1.5rem;"></i>
                    <p style="color: var(--color-text-secondary); font-size: 1.1rem;">暂无视频资源</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- 分页 -->
        @if($videos->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $videos->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    /* 播放按钮悬浮效果 */
    .play-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0,0,0,0.2);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .lecture-card:hover .play-overlay {
        opacity: 1;
    }

    .play-btn {
        width: 70px;
        height: 70px;
        background: rgba(255,255,255,0.95);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        transition: transform 0.3s ease;
    }

    .play-btn i {
        font-size: 2rem;
        color: var(--color-primary);
        margin-left: 4px;
    }

    .lecture-card:hover .play-btn {
        transform: scale(1.1);
    }
</style>
@endpush
