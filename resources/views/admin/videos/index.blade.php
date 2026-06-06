@extends('layouts.admin')

@section('title', '视频管理')

@section('content')
<!-- 页面标题 -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2>视频管理</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">仪表盘</a></li>
                <li class="breadcrumb-item active">视频管理</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.videos.create') }}" class="btn btn-admin-primary">
        <i class="bi bi-plus-lg me-2"></i>添加视频
    </a>
</div>

<!-- 搜索和筛选 -->
<div class="table-card mb-4">
    <div class="card-body" style="padding: 1rem 1.5rem;">
        <form action="{{ route('admin.videos.index') }}" method="GET">
            <!-- 第一行：关键词搜索 + 状态 -->
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                           placeholder="搜索视频标题...">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">全部状态</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>启用</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>禁用</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-admin-primary w-100">
                        <i class="bi bi-search me-1"></i>搜索
                    </button>
                </div>
                <div class="col-md-2">
                    @if(request()->hasAny(['search', 'lecture_id', 'status']))
                        <a href="{{ route('admin.videos.index') }}" class="btn btn-admin-outline w-100">
                            <i class="bi bi-x-lg me-1"></i>重置
                        </a>
                    @endif
                </div>
            </div>
            <!-- 第二行：按讲座筛选 -->
            <div class="row g-3">
                <div class="col-md-6">
                    <select name="lecture_id" class="form-select" onchange="this.form.submit()">
                        <option value="">全部讲座</option>
                        @foreach($lectures as $id => $title)
                            <option value="{{ $id }}" {{ request('lecture_id') == $id ? 'selected' : '' }}>{{ Str::limit($title, 40) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- 当前筛选条件 -->
            @if(request()->hasAny(['search', 'lecture_id', 'status']))
                <div class="mt-3">
                    <span style="color: var(--color-text-secondary); font-size: 0.85rem;">
                        当前筛选：
                        @if(request('search'))
                            <span class="badge me-1" style="background: var(--color-sage-light); color: #fff;">关键词: {{ request('search') }}</span>
                        @endif
                        @if(request('lecture_id'))
                            <span class="badge me-1" style="background: var(--color-primary); color: #fff;">讲座: {{ Str::limit($lectures->get(request('lecture_id')) ?? '', 20) }}</span>
                        @endif
                        @if(request('status') !== null && request('status') !== '')
                            <span class="badge me-1" style="background: var(--color-text-light); color: #fff;">
                                状态: {{ request('status') === '1' ? '启用' : '禁用' }}
                            </span>
                        @endif
                    </span>
                </div>
            @endif
        </form>
    </div>
</div>

<!-- 视频列表 -->
<div class="table-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="bi bi-play-circle me-2" style="color: var(--color-sage);"></i>视频列表</h5>
        <span style="color: var(--color-text-secondary); font-size: 0.85rem;">共 {{ $videos->total() }} 个视频</span>
    </div>
    <div class="card-body">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width: 100px;">封面</th>
                    <th>标题</th>
                    <th>所属讲座</th>
                    <th style="width: 80px;">时长</th>
                    <th style="width: 80px;">状态</th>
                    <th style="width: 150px;">操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($videos as $video)
                <tr>
                    <td>
                        <div class="position-relative">
                            @if($video->cover_image)
                                <img src="{{ $video->thumb_cover }}" class="img-preview" alt="{{ $video->title }}">
                            @else
                                <div class="img-preview d-flex align-items-center justify-content-center" style="background: var(--color-bg-warm);">
                                    <i class="bi bi-play-circle" style="color: var(--color-text-light);"></i>
                                </div>
                            @endif
                            <div class="position-absolute bottom-0 end-0" style="margin: 2px;">
                                <span style="background: rgba(0,0,0,0.7); color: #fff; font-size: 0.6rem; padding: 2px 5px; border-radius: var(--radius-pill);">
                                    {{ $video->formatted_duration }}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-weight: 500;">{{ Str::limit($video->title, 50) }}</span>
                    </td>
                    <td>
                        @if($video->lecture)
                            <a href="{{ route('admin.lectures.edit', $video->lecture) }}" style="color: var(--color-primary); text-decoration: none;">
                                {{ Str::limit($video->lecture->title, 30) }}
                            </a>
                        @else
                            <span style="color: var(--color-text-light);">-</span>
                        @endif
                    </td>
                    <td>{{ $video->formatted_duration }}</td>
                    <td>
                        @if($video->status)
                            <span class="badge-status active">启用</span>
                        @else
                            <span class="badge-status inactive">禁用</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.videos.edit', $video) }}" class="btn btn-action btn-action-edit btn-sm">
                                <i class="bi bi-pencil me-1"></i>编辑
                            </a>
                            <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" class="d-inline" onsubmit="return confirm('确定删除此视频吗？')">
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
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="bi bi-play-circle d-block"></i>
                            <p>{{ request('search') ? '未找到匹配的视频' : '暂无视频数据' }}</p>
                            @if(!request('search'))
                                <a href="{{ route('admin.videos.create') }}" class="btn btn-admin-primary">
                                    <i class="bi bi-plus-lg me-2"></i>添加第一个视频
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
@if($videos->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $videos->links() }}
</div>
@endif
@endsection
