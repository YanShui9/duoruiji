@extends('layouts.app')

@section('title', $expert->name . ' - 多瑞吉医学名家讲堂')

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
                        <a href="{{ route('experts.index') }}" style="color: var(--color-text-secondary); text-decoration: none; font-size: 0.9rem;">名家风采</a>
                    </li>
                    <li class="breadcrumb-item active" style="color: var(--color-text); font-size: 0.9rem;">{{ $expert->name }}</li>
                </ol>
            </nav>
            <a href="{{ route('experts.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> 返回
            </a>
        </div>
    </div>
</section>

<div style="padding: var(--section-padding) 0;">
    <div class="container" style="max-width: 1200px;">
        <div class="row g-4">
            <div class="col-lg-4">
                <!-- 专家信息卡片 -->
                <div class="lecture-card mb-4 text-center" style="padding: 2.5rem;">
                    @if($expert->avatar)
                        <img src="{{ $expert->thumb_avatar }}" class="rounded-circle mb-4"
                             style="width: 160px; height: 160px; object-fit: cover; box-shadow: 0 8px 30px rgba(0,0,0,0.1);"
                             alt="{{ $expert->name }}">
                    @else
                        <div class="mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle"
                             style="width: 160px; height: 160px; background: linear-gradient(135deg, var(--color-sage-light), var(--color-sage));">
                            <i class="bi bi-person text-white" style="font-size: 3.5rem;"></i>
                        </div>
                    @endif

                    <h2 class="font-serif fw-semibold mb-2">{{ $expert->name }}</h2>
                    <p class="mb-3">
                        <span style="background: var(--color-bg-warm); color: var(--color-primary); font-weight: 600; padding: 0.5rem 1.25rem; border-radius: var(--radius-pill); font-size: 0.85rem;">
                            {{ $expert->title }}
                        </span>
                    </p>
                    <div class="d-flex flex-column gap-2">
                        <p style="color: var(--color-text-secondary); margin: 0;">
                            <i class="bi bi-hospital me-2" style="color: var(--color-sage);"></i>
                            {{ $expert->hospital }}
                        </p>
                        <p style="color: var(--color-text-secondary); margin: 0;">
                            <i class="bi bi-geo-alt me-2" style="color: var(--color-sage);"></i>
                            {{ $expert->department }}
                        </p>
                    </div>
                </div>

                <!-- 专家简介 -->
                @if($expert->bio)
                <div class="lecture-card mb-4">
                    <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--color-bg-warm);">
                        <h5 class="font-serif fw-semibold mb-0" style="font-size: 1.25rem;">
                            <i class="bi bi-person-lines-fill me-2" style="color: var(--color-sage);"></i>
                            专家简介
                        </h5>
                    </div>
                    <div style="padding: 2rem;">
                        <div style="line-height: 1.9; color: var(--color-text-secondary); font-size: 1.05rem;">
                            {!! preg_replace('/<(\w+)\s[^>]*>/', '<$1>', strip_tags($expert->bio, '<p><br><strong><em><b><i><ul><ol><li><h1><h2><h3><h4><h5><h6>')) !!}
                        </div>
                    </div>
                </div>
                @endif

                <!-- 相关专家 -->
                @if($relatedExperts->count() > 0)
                <div class="lecture-card">
                    <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--color-bg-warm);">
                        <h5 class="font-serif fw-semibold mb-0" style="font-size: 1.25rem;">
                            <i class="bi bi-people me-2" style="color: var(--color-sage);"></i>
                            相关专家
                        </h5>
                    </div>
                    <div style="padding: 1.5rem 2rem;">
                        @foreach($relatedExperts as $related)
                        <div class="d-flex align-items-center {{ !$loop->last ? 'mb-3 pb-3' : '' }}"
                             style="{{ !$loop->last ? 'border-bottom: 1px solid var(--color-bg-warm);' : '' }}">
                            @if($related->avatar)
                                <img src="{{ $related->thumb_avatar }}" class="rounded-circle me-3"
                                     style="width: 45px; height: 45px; object-fit: cover;"
                                     alt="{{ $related->name }}">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                     style="width: 45px; height: 45px; background: var(--color-sage-light); color: #fff;">
                                    <i class="bi bi-person"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="fw-semibold mb-1">
                                    <a href="{{ route('experts.show', $related) }}" style="color: var(--color-text); text-decoration: none;">
                                        {{ $related->name }}
                                    </a>
                                </h6>
                                <small style="color: var(--color-text-secondary);">{{ $related->title }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-8">
                <!-- 参与的讲座 -->
                <div class="lecture-card">
                    <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--color-bg-warm);">
                        <h5 class="font-serif fw-semibold mb-0" style="font-size: 1.25rem;">
                            <i class="bi bi-camera-video me-2" style="color: var(--color-sage);"></i>
                            参与的讲座
                        </h5>
                    </div>
                    <div style="padding: 2rem;">
                        @forelse($lectures as $lecture)
                        <div class="d-flex mb-4 pb-4 {{ !$loop->last ? '' : '' }}"
                             style="{{ !$loop->last ? 'border-bottom: 1px solid var(--color-bg-warm);' : '' }}">
                            @if($lecture->cover_image)
                                <img src="{{ $lecture->thumb_cover }}" class="rounded-3 me-4"
                                     style="width: 160px; height: 110px; object-fit: cover;"
                                     alt="{{ $lecture->title }}">
                            @else
                                <div class="rounded-3 d-flex align-items-center justify-content-center me-4"
                                     style="width: 160px; height: 110px; background: var(--color-bg-warm);">
                                    <i class="bi bi-camera" style="font-size: 2rem; color: var(--color-text-light);"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h5 class="fw-semibold mb-2">
                                    <a href="{{ route('lectures.show', $lecture) }}" style="color: var(--color-text); text-decoration: none;">
                                        {{ $lecture->title }}
                                    </a>
                                </h5>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    @if($lecture->live_start_time)
                                        <small style="color: var(--color-text-secondary);">
                                            <i class="bi bi-calendar me-1"></i>
                                            {{ $lecture->live_start_time->format('Y-m-d H:i') }}
                                        </small>
                                    @endif

                                    @if($lecture->status === 1)
                                        <span class="badge-shadcn badge-live" style="font-size: 0.75rem; padding: 0.25rem 0.75rem;">
                                            <i class="bi bi-broadcast me-1"></i> 直播中
                                        </span>
                                    @elseif($lecture->status === 0 && $lecture->live_start_time && $lecture->live_start_time->isFuture())
                                        <span class="badge-shadcn badge-upcoming" style="font-size: 0.75rem; padding: 0.25rem 0.75rem;">
                                            <i class="bi bi-clock me-1"></i> 即将开始
                                        </span>
                                    @elseif($lecture->status === 2)
                                        <span class="badge-shadcn badge-ended" style="font-size: 0.75rem; padding: 0.25rem 0.75rem;">
                                            <i class="bi bi-check-circle me-1"></i> 已结束
                                        </span>
                                    @endif
                                </div>
                                <p style="color: var(--color-text-secondary); line-height: 1.7; margin: 0;">
                                    {{ Str::limit(strip_tags($lecture->description), 150) }}
                                </p>
                            </div>
                        </div>
                        @empty
                        <div class="empty-state" style="padding: 4rem 2rem;">
                            <i class="bi bi-camera-video d-block" style="font-size: 3rem; color: var(--color-sage-light); margin-bottom: 1.5rem;"></i>
                            <p style="color: var(--color-text-secondary); margin: 0;">暂无讲座记录</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
