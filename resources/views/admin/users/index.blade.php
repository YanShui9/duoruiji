@extends('layouts.admin')

@section('title', '用户管理')

@section('content')
<!-- 页面标题 -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2>用户管理</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">仪表盘</a></li>
                <li class="breadcrumb-item active">用户管理</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-admin-primary">
        <i class="bi bi-person-plus me-2"></i>添加用户
    </a>
</div>

<!-- 搜索框 -->
<div class="table-card mb-4">
    <div class="card-body" style="padding: 1rem 1.5rem;">
        <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-3">
            <div class="flex-grow-1">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                       placeholder="搜索用户名、邮箱...">
            </div>
            <button type="submit" class="btn btn-admin-primary">
                <i class="bi bi-search me-1"></i>搜索
            </button>
            @if(request('search'))
                <a href="{{ route('admin.users.index') }}" class="btn btn-admin-outline">
                    <i class="bi bi-x-lg me-1"></i>清除
                </a>
            @endif
        </form>
    </div>
</div>

<!-- 用户列表 -->
<div class="table-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="bi bi-person-gear me-2" style="color: var(--color-sage);"></i>用户列表</h5>
        <span style="color: var(--color-text-secondary); font-size: 0.85rem;">共 {{ $users->total() }} 个用户</span>
    </div>
    <div class="card-body">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>用户</th>
                    <th>邮箱</th>
                    <th>注册时间</th>
                    <th style="width: 150px;">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($user->avatar)
                                <img src="{{ $user->thumb_avatar }}" class="rounded-circle me-3"
                                     style="width: 40px; height: 40px; object-fit: cover; border: 2px solid var(--color-border);">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                     style="width: 40px; height: 40px; background: var(--color-primary); color: #fff; font-weight: 600; font-size: 0.9rem;">
                                    {{ mb_substr($user->name, 0, 1) }}
                                </div>
                            @endif
                            <span style="font-weight: 500;">{{ $user->name }}</span>
                            @if($user->id === auth()->id())
                                <span style="background: var(--color-sage-light); color: #fff; font-size: 0.7rem; padding: 0.2rem 0.5rem; border-radius: var(--radius-pill); margin-left: 0.5rem;">当前用户</span>
                            @endif
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-action btn-action-edit btn-sm">
                                <i class="bi bi-pencil me-1"></i>编辑
                            </a>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('确定删除此用户吗？')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-action-delete btn-sm">
                                        <i class="bi bi-trash me-1"></i>删除
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-action btn-sm" disabled style="opacity: 0.5; cursor: not-allowed;">
                                    <i class="bi bi-trash me-1"></i>删除
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- 分页 -->
@if($users->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $users->links() }}
</div>
@endif
@endsection
