@extends('layouts.admin')

@section('title', '视频管理')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>视频管理</h2>
    <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> 添加视频
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>封面</th>
                    <th>标题</th>
                    <th>所属讲座</th>
                    <th>时长</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($videos as $video)
                <tr>
                    <td>{{ $video->id }}</td>
                    <td>
                        @if($video->cover_image)
                            <img src="{{ Storage::url($video->cover_image) }}" alt="{{ $video->title }}" width="80" height="45">
                        @else
                            <span class="text-muted">无</span>
                        @endif
                    </td>
                    <td>{{ $video->title }}</td>
                    <td>{{ $video->lecture->title ?? '-' }}</td>
                    <td>{{ $video->formatted_duration }}</td>
                    <td>
                        <span class="badge bg-{{ $video->status ? 'success' : 'secondary' }}">
                            {{ $video->status ? '启用' : '禁用' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.videos.edit', $video) }}" class="btn btn-sm btn-outline-primary">编辑</a>
                        <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" class="d-inline" onsubmit="return confirm('确定删除吗？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">删除</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $videos->links() }}
    </div>
</div>
@endsection
