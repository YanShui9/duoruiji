@extends('layouts.admin')

@section('title', '专家管理')

@section('content')
<!-- 页面标题 -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2>专家管理</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">仪表盘</a></li>
                <li class="breadcrumb-item active">专家管理</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.experts.create') }}" class="btn btn-admin-primary">
        <i class="bi bi-plus-lg me-2"></i>添加专家
    </a>
</div>

<!-- 搜索和筛选 -->
<div class="table-card mb-4">
    <div class="card-body" style="padding: 1rem 1.5rem;">
        <form action="{{ route('admin.experts.index') }}" method="GET">
            <!-- 第一行：关键词搜索 + 状态 -->
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                           placeholder="搜索专家姓名...">
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
                    @if(request()->hasAny(['search', 'title', 'hospital', 'department', 'status']))
                        <a href="{{ route('admin.experts.index') }}" class="btn btn-admin-outline w-100">
                            <i class="bi bi-x-lg me-1"></i>重置
                        </a>
                    @endif
                </div>
            </div>
            <!-- 第二行：分类筛选 -->
            <div class="row g-3">
                <div class="col-md-4">
                    <select name="hospital" class="form-select" onchange="this.form.submit()">
                        <option value="">全部医院</option>
                        @foreach($hospitals as $h)
                            <option value="{{ $h }}" {{ request('hospital') === $h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="department" class="form-select" onchange="this.form.submit()">
                        <option value="">全部科室</option>
                        @foreach($departments as $d)
                            <option value="{{ $d }}" {{ request('department') === $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="title" class="form-select" onchange="this.form.submit()">
                        <option value="">全部职称</option>
                        @foreach($titles as $t)
                            <option value="{{ $t }}" {{ request('title') === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- 当前筛选条件 -->
            @if(request()->hasAny(['search', 'title', 'hospital', 'department', 'status']))
                <div class="mt-3">
                    <span style="color: var(--color-text-secondary); font-size: 0.85rem;">
                        当前筛选：
                        @if(request('search'))
                            <span class="badge me-1" style="background: var(--color-sage-light); color: #fff;">姓名: {{ request('search') }}</span>
                        @endif
                        @if(request('hospital'))
                            <span class="badge me-1" style="background: var(--color-primary); color: #fff;">医院: {{ request('hospital') }}</span>
                        @endif
                        @if(request('department'))
                            <span class="badge me-1" style="background: var(--color-primary); color: #fff;">科室: {{ request('department') }}</span>
                        @endif
                        @if(request('title'))
                            <span class="badge me-1" style="background: var(--color-accent); color: #fff;">职称: {{ request('title') }}</span>
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

<!-- 专家列表 -->
<div class="table-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="bi bi-people me-2" style="color: var(--color-sage);"></i>专家列表</h5>
        <span style="color: var(--color-text-secondary); font-size: 0.85rem;">共 {{ $experts->total() }} 位专家</span>
    </div>
    <div class="card-body">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width: 80px;">头像</th>
                    <th>姓名</th>
                    <th>职称</th>
                    <th>医院</th>
                    <th>科室</th>
                    <th style="width: 80px;">状态</th>
                    <th style="width: 150px;">操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($experts as $expert)
                <tr>
                    <td>
                        @if($expert->avatar)
                            <img src="{{ $expert->thumb_avatar }}" class="img-preview-sm" alt="{{ $expert->name }}">
                        @else
                            <div class="img-preview-sm d-flex align-items-center justify-content-center" style="background: var(--color-bg-warm);">
                                <i class="bi bi-person" style="color: var(--color-text-light);"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <span style="font-weight: 500;">{{ $expert->name }}</span>
                    </td>
                    <td>{{ $expert->title }}</td>
                    <td>{{ $expert->hospital }}</td>
                    <td>{{ $expert->department }}</td>
                    <td>
                        @if($expert->status)
                            <span class="badge-status active">启用</span>
                        @else
                            <span class="badge-status inactive">禁用</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.experts.edit', $expert) }}" class="btn btn-action btn-action-edit btn-sm">
                                <i class="bi bi-pencil me-1"></i>编辑
                            </a>
                            <form action="{{ route('admin.experts.destroy', $expert) }}" method="POST" class="d-inline" onsubmit="return confirm('确定删除此专家吗？')">
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
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-people d-block"></i>
                            <p>{{ request('search') ? '未找到匹配的专家' : '暂无专家数据' }}</p>
                            @if(!request('search'))
                                <a href="{{ route('admin.experts.create') }}" class="btn btn-admin-primary">
                                    <i class="bi bi-plus-lg me-2"></i>添加第一位专家
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
@if($experts->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $experts->links() }}
</div>
@endif
@endsection
