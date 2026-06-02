@extends('layouts.admin')

@section('title', '专家管理')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>专家管理</h2>
    <a href="{{ route('admin.experts.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> 添加专家
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>头像</th>
                    <th>姓名</th>
                    <th>职称</th>
                    <th>医院</th>
                    <th>科室</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($experts as $expert)
                <tr>
                    <td>{{ $expert->id }}</td>
                    <td>
                        @if($expert->avatar)
                            <img src="{{ Storage::url($expert->avatar) }}" alt="{{ $expert->name }}" width="50" height="50" class="rounded-circle">
                        @else
                            <span class="text-muted">无</span>
                        @endif
                    </td>
                    <td>{{ $expert->name }}</td>
                    <td>{{ $expert->title }}</td>
                    <td>{{ $expert->hospital }}</td>
                    <td>{{ $expert->department }}</td>
                    <td>
                        <span class="badge bg-{{ $expert->status ? 'success' : 'secondary' }}">
                            {{ $expert->status ? '启用' : '禁用' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.experts.edit', $expert) }}" class="btn btn-sm btn-outline-primary">编辑</a>
                        <form action="{{ route('admin.experts.destroy', $expert) }}" method="POST" class="d-inline" onsubmit="return confirm('确定删除吗？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">删除</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">暂无专家数据</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $experts->links() }}
    </div>
</div>
@endsection
