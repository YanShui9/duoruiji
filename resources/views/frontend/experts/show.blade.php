@extends('layouts.app')

@section('title', $expert->name . ' - 多瑞吉医学名家讲堂')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">首页</a></li>
            <li class="breadcrumb-item"><a href="{{ route('experts.index') }}">名家风采</a></li>
            <li class="breadcrumb-item active">{{ $expert->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-4">
            <!-- 专家信息卡片 -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    @if($expert->avatar)
                        <img src="{{ Storage::url($expert->avatar) }}" class="rounded-circle mb-3" style="width: 200px; height: 200px; object-fit: cover;" alt="{{ $expert->name }}">
                    @else
                        <div class="mx-auto mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 200px; height: 200px;">
                            <i class="bi bi-person display-1 text-muted"></i>
                        </div>
                    @endif
                    <h3>{{ $expert->name }}</h3>
                    <p class="text-muted">{{ $expert->title }}</p>
                    <p><i class="bi bi-hospital"></i> {{ $expert->hospital }}</p>
                    <p><i class="bi bi-geo-alt"></i> {{ $expert->department }}</p>
                </div>
            </div>

            <!-- 专家简介 -->
            @if($expert->bio)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">专家简介</h5>
                </div>
                <div class="card-body">
                    <p>{!! nl2br(e($expert->bio)) !!}</p>
                </div>
            </div>
            @endif

            <!-- 相关专家 -->
            @if($relatedExperts->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">相关专家</h5>
                </div>
                <div class="card-body">
                    @foreach($relatedExperts as $related)
                    <div class="d-flex align-items-center mb-3">
                        @if($related->avatar)
                            <img src="{{ Storage::url($related->avatar) }}" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;" alt="{{ $related->name }}">
                        @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-person text-muted"></i>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-0"><a href="{{ route('experts.show', $related) }}">{{ $related->name }}</a></h6>
                            <small class="text-muted">{{ $related->title }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-8">
            <!-- 参与的讲座 -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">参与的讲座</h5>
                </div>
                <div class="card-body">
                    @forelse($lectures as $lecture)
                    <div class="d-flex mb-4 pb-4 border-bottom">
                        @if($lecture->cover_image)
                            <img src="{{ Storage::url($lecture->cover_image) }}" class="rounded me-3" style="width: 150px; height: 100px; object-fit: cover;" alt="{{ $lecture->title }}">
                        @else
                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 150px; height: 100px;">
                                <i class="bi bi-camera text-muted"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h5><a href="{{ route('lectures.show', $lecture) }}">{{ $lecture->title }}</a></h5>
                            <p class="text-muted mb-2">
                                <small>
                                    @if($lecture->live_start_time)
                                        <i class="bi bi-calendar"></i> {{ $lecture->live_start_time->format('Y-m-d H:i') }}
                                    @endif

                                    @if($lecture->status === 1)
                                        <span class="badge bg-danger ms-2">直播中</span>
                                    @elseif($lecture->status === 2)
                                        <span class="badge bg-secondary ms-2">已结束</span>
                                    @endif
                                </small>
                            </p>
                            <p class="mb-0">{{ Str::limit($lecture->description, 150) }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="bi bi-inbox display-4 text-muted"></i>
                        <p class="mt-2 text-muted">暂无讲座记录</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
