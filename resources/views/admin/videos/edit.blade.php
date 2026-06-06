@extends('layouts.admin')

@section('title', '编辑视频')

@section('content')
<!-- 页面标题 -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2>编辑视频</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">仪表盘</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.videos.index') }}">视频管理</a></li>
                <li class="breadcrumb-item active">编辑视频</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.videos.index') }}" class="btn btn-admin-outline">
        <i class="bi bi-arrow-left me-2"></i>返回列表
    </a>
</div>

<!-- 表单 -->
<div class="form-card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-pencil me-2" style="color: var(--color-sage);"></i>编辑视频信息</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.videos.update', $video) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.videos._form')
            <div class="d-flex gap-3 mt-4 pt-4" style="border-top: 1px solid var(--color-border);">
                <button type="submit" class="btn btn-admin-primary">
                    <i class="bi bi-check-lg me-2"></i>更新
                </button>
                <a href="{{ route('admin.videos.index') }}" class="btn btn-admin-outline">
                    <i class="bi bi-x-lg me-2"></i>取消
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
