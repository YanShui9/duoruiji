@extends('layouts.admin')

@section('title', '仪表盘')
@section('page-title', '仪表盘')

@section('breadcrumb')
    <li class="breadcrumb-item active">仪表盘</li>
@endsection

@section('content')
<!-- 统计卡片 -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['experts'] }}</h3>
                <p>专家总数</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="bi bi-camera-video"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['lectures'] }}</h3>
                <p>讲座总数</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="bi bi-play-circle"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['videos'] }}</h3>
                <p>视频总数</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card danger">
            <div class="stat-icon">
                <i class="bi bi-broadcast"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['live_now'] }}</h3>
                <p>正在直播</p>
            </div>
        </div>
    </div>
</div>

<!-- 快速操作和最近讲座 -->
<div class="row g-4">
    <!-- 快速操作 -->
    <div class="col-lg-4">
        <div class="form-card">
            <div class="card-header">
                <h5><i class="bi bi-lightning me-2" style="color: var(--color-accent);"></i>快速操作</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="{{ route('admin.experts.create') }}" class="btn-admin-primary justify-content-center">
                        <i class="bi bi-person-plus"></i>
                        添加专家
                    </a>
                    <a href="{{ route('admin.lectures.create') }}" class="btn-admin-accent justify-content-center">
                        <i class="bi bi-plus-circle"></i>
                        创建讲座
                    </a>
                    <a href="{{ route('admin.videos.create') }}" class="btn-admin-outline justify-content-center">
                        <i class="bi bi-upload"></i>
                        上传视频
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="btn-admin-outline justify-content-center">
                        <i class="bi bi-person-plus"></i>
                        添加用户
                    </a>
                </div>

                <hr style="border-color: var(--color-border); margin: 1.5rem 0;">

                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('admin.experts.index') }}" class="d-flex align-items-center gap-2 text-decoration-none" style="color: var(--color-text-secondary); font-size: 0.9rem;">
                        <i class="bi bi-people" style="color: var(--color-sage);"></i>
                        管理专家
                        <i class="bi bi-chevron-right ms-auto" style="font-size: 0.75rem;"></i>
                    </a>
                    <a href="{{ route('admin.lectures.index') }}" class="d-flex align-items-center gap-2 text-decoration-none" style="color: var(--color-text-secondary); font-size: 0.9rem;">
                        <i class="bi bi-camera-video" style="color: var(--color-sage);"></i>
                        管理讲座
                        <i class="bi bi-chevron-right ms-auto" style="font-size: 0.75rem;"></i>
                    </a>
                    <a href="{{ route('admin.videos.index') }}" class="d-flex align-items-center gap-2 text-decoration-none" style="color: var(--color-text-secondary); font-size: 0.9rem;">
                        <i class="bi bi-play-circle" style="color: var(--color-sage);"></i>
                        管理视频
                        <i class="bi bi-chevron-right ms-auto" style="font-size: 0.75rem;"></i>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="d-flex align-items-center gap-2 text-decoration-none" style="color: var(--color-text-secondary); font-size: 0.9rem;">
                        <i class="bi bi-person-gear" style="color: var(--color-sage);"></i>
                        管理用户
                        <i class="bi bi-chevron-right ms-auto" style="font-size: 0.75rem;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 最近讲座 -->
    <div class="col-lg-8">
        <div class="table-card">
            <div class="card-header">
                <h5><i class="bi bi-clock-history me-2" style="color: var(--color-sage);"></i>最近讲座</h5>
                <a href="{{ route('admin.lectures.index') }}" class="btn-admin-outline" style="padding: 0.5rem 1rem; font-size: 0.8rem;">
                    查看全部
                </a>
            </div>
            <div class="card-body">
                @php
                    $recentLectures = \App\Models\Lecture::latest()->take(5)->get();
                @endphp

                @if($recentLectures->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>讲座名称</th>
                                    <th>状态</th>
                                    <th>时间</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentLectures as $lecture)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($lecture->cover_image)
                                                    <img src="{{ $lecture->thumb_cover }}" class="img-preview me-3" alt="">
                                                @else
                                                    <div class="img-preview me-3 d-flex align-items-center justify-content-center" style="background: var(--color-bg-warm);">
                                                        <i class="bi bi-camera" style="color: var(--color-text-light);"></i>
                                                    </div>
                                                @endif
                                                <span class="fw-medium">{{ Str::limit($lecture->title, 30) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($lecture->status === 1)
                                                <span class="badge-status live">
                                                    <i class="bi bi-broadcast" style="font-size: 0.6rem;"></i> 直播中
                                                </span>
                                            @elseif($lecture->status === 0)
                                                <span class="badge-status upcoming">未开始</span>
                                            @else
                                                <span class="badge-status ended">已结束</span>
                                            @endif
                                        </td>
                                        <td style="color: var(--color-text-secondary); font-size: 0.85rem;">
                                            {{ $lecture->live_start_time ? $lecture->live_start_time->format('m/d H:i') : '-' }}
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.lectures.edit', $lecture) }}" class="btn-action btn-action-edit">
                                                <i class="bi bi-pencil"></i> 编辑
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state" style="padding: 3rem;">
                        <i class="bi bi-camera-video"></i>
                        <h5>暂无讲座</h5>
                        <p>点击上方按钮创建第一个讲座</p>
                        <a href="{{ route('admin.lectures.create') }}" class="btn-admin-primary">
                            <i class="bi bi-plus-circle"></i>
                            创建讲座
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- 系统信息 -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="form-card">
            <div class="card-header">
                <h5><i class="bi bi-info-circle me-2" style="color: var(--color-sage);"></i>系统信息</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-6 mb-3">
                        <div style="color: var(--color-text-secondary); font-size: 0.8rem; margin-bottom: 0.25rem;">Laravel 版本</div>
                        <div style="font-weight: 500;">{{ app()->version() }}</div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div style="color: var(--color-text-secondary); font-size: 0.8rem; margin-bottom: 0.25rem;">PHP 版本</div>
                        <div style="font-weight: 500;">{{ phpversion() }}</div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div style="color: var(--color-text-secondary); font-size: 0.8rem; margin-bottom: 0.25rem;">MySQL 版本</div>
                        <div style="font-weight: 500;">{{ \Illuminate\Support\Facades\DB::selectOne('SELECT VERSION() as version')->version }}</div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div style="color: var(--color-text-secondary); font-size: 0.8rem; margin-bottom: 0.25rem;">服务器时间</div>
                        <div style="font-weight: 500;">{{ now()->format('Y-m-d H:i:s') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
