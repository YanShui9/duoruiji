@extends('layouts.admin')

@section('title', '讲座管理')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>讲座管理</h2>
    <a href="{{ route('admin.lectures.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> 添加讲座
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
                    <th>封面</th>
                    <th>标题</th>
                    <th>直播时间</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lectures as $lecture)
                <tr>
                    <td>{{ $lecture->id }}</td>
                    <td>
                        @if($lecture->cover_image)
                            <img src="{{ Storage::url($lecture->cover_image) }}" alt="{{ $lecture->title }}" width="80" height="45">
                        @else
                            <span class="text-muted">无</span>
                        @endif
                    </td>
                    <td>{{ $lecture->title }}</td>
                    <td>{{ $lecture->live_start_time ? $lecture->live_start_time->format('Y-m-d H:i') : '-' }}</td>
                    <td>
                        @switch($lecture->status)
                            @case(0)
                                <span class="badge bg-warning">未开始</span>
                                @break
                            @case(1)
                                <span class="badge bg-danger">直播中</span>
                                @break
                            @case(2)
                                <span class="badge bg-secondary">已结束</span>
                                @break
                        @endswitch
                    </td>
                    <td>
                        <a href="{{ route('admin.lectures.edit', $lecture) }}" class="btn btn-sm btn-outline-primary">编辑</a>
                        <form action="{{ route('admin.lectures.destroy', $lecture) }}" method="POST" class="d-inline" onsubmit="return confirm('确定删除吗？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">删除</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $lectures->links() }}
    </div>
</div>
@endsection
