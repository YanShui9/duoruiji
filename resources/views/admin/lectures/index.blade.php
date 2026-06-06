@extends('layouts.admin')

@section('title', '直播管理')

@section('content')
<!-- 页面标题 -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2>直播管理</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">仪表盘</a></li>
                <li class="breadcrumb-item active">直播管理</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.lectures.create') }}" class="btn btn-admin-primary">
        <i class="bi bi-plus-lg me-2"></i>创建讲座
    </a>
</div>

<!-- 搜索和筛选 -->
<div class="table-card mb-4">
    <div class="card-body" style="padding: 1rem 1.5rem;">
        <form action="{{ route('admin.lectures.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                           placeholder="搜索讲座标题...">
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select">
                        <option value="">全部分类</option>
                        @foreach(\App\Models\Lecture::getCategories() as $key => $name)
                            <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">全部状态</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>直播中</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>未开始</option>
                        <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>已结束</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-admin-primary flex-grow-1">
                        <i class="bi bi-search me-1"></i>搜索
                    </button>
                    @if(request('search') || request('category') || request('status') !== null && request('status') !== '')
                        <a href="{{ route('admin.lectures.index') }}" class="btn btn-admin-outline">
                            <i class="bi bi-x-lg me-1"></i>重置
                        </a>
                    @endif
                </div>
            </div>
            @if(request('search') || request('status') !== null && request('status') !== '')
                <div class="mt-2">
                    <span style="color: var(--color-text-secondary); font-size: 0.85rem;">
                        当前筛选：
                        @if(request('search'))
                            <span class="badge" style="background: var(--color-sage-light); color: #fff;">关键词: {{ request('search') }}</span>
                        @endif
                        @if(request('status') !== null && request('status') !== '')
                            <span class="badge" style="background: var(--color-primary); color: #fff;">
                                状态: {{ request('status') === '1' ? '直播中' : (request('status') === '0' ? '未开始' : '已结束') }}
                            </span>
                        @endif
                    </span>
                </div>
            @endif
        </form>
    </div>
</div>

<!-- 讲座列表 -->
<div class="table-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="bi bi-camera-video me-2" style="color: var(--color-sage);"></i>讲座列表</h5>
        <span style="color: var(--color-text-secondary); font-size: 0.85rem;">共 {{ $lectures->total() }} 场讲座</span>
    </div>
    <div class="card-body">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width: 100px;">封面</th>
                    <th>标题</th>
                    <th style="width: 100px;">分类</th>
                    <th>直播时间</th>
                    <th style="width: 100px;">状态</th>
                    <th style="width: 150px;">操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lectures as $lecture)
                <tr>
                    <td>
                        @if($lecture->cover_image)
                            <img src="{{ $lecture->thumb_cover }}" class="img-preview" alt="{{ $lecture->title }}">
                        @else
                            <div class="img-preview d-flex align-items-center justify-content-center" style="background: var(--color-bg-warm);">
                                <i class="bi bi-camera" style="color: var(--color-text-light);"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <span style="font-weight: 500;">{{ Str::limit($lecture->title, 40) }}</span>
                    </td>
                    <td>
                        @if($lecture->category)
                            <span class="badge" style="background: var(--color-sage-light); color: #fff;">
                                {{ $lecture->category_name }}
                            </span>
                        @else
                            <span style="color: var(--color-text-light);">-</span>
                        @endif
                    </td>
                    <td>
                        @if($lecture->live_start_time)
                            <div style="font-size: 0.85rem;">
                                <div>{{ $lecture->live_start_time->format('Y-m-d H:i') }}</div>
                                @if($lecture->live_end_time)
                                    @if($lecture->live_start_time->format('Y-m-d') === $lecture->live_end_time->format('Y-m-d'))
                                        <div style="color: var(--color-text-light);">至 {{ $lecture->live_end_time->format('H:i') }}</div>
                                    @else
                                        <div style="color: var(--color-text-light);">至 {{ $lecture->live_end_time->format('Y-m-d H:i') }}</div>
                                    @endif
                                @endif
                            </div>
                        @else
                            <span style="color: var(--color-text-light);">-</span>
                        @endif
                    </td>
                    <td>
                        @if($lecture->status === 0)
                            <span class="badge-status upcoming">未开始</span>
                        @elseif($lecture->status === 1)
                            <span class="badge-status live">直播中</span>
                        @else
                            <span class="badge-status ended">已结束</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.lectures.edit', $lecture) }}" class="btn btn-action btn-action-edit btn-sm">
                                <i class="bi bi-pencil me-1"></i>编辑
                            </a>
                            <form action="{{ route('admin.lectures.destroy', $lecture) }}" method="POST" class="d-inline" onsubmit="return confirm('确定删除此讲座吗？关联的视频也会被删除！')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-action btn-action-delete btn-sm">
                                    <i class="bi bi-trash me-1"></i>删除
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="bi bi-camera-video d-block"></i>
                            <p>{{ (request('search') || request('status') !== null && request('status') !== '') ? '未找到匹配的讲座' : '暂无讲座数据' }}</p>
                            @if(!request('search') && (request('status') === null || request('status') === ''))
                                <a href="{{ route('admin.lectures.create') }}" class="btn btn-admin-primary">
                                    <i class="bi bi-plus-lg me-2"></i>创建第一场讲座
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- 分页 -->
@if($lectures->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $lectures->links() }}
</div>
@endif
@endsection
