@extends('layouts.app')

@section('title', '名家讲堂 - 多瑞吉医学名家讲堂')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-5">名家讲堂</h1>

    <div class="row">
        @forelse($lectures as $lecture)
        <div class="col-md-4 mb-4">
            <div class="card card-hover h-100">
                @if($lecture->cover_image)
                    <img src="{{ Storage::url($lecture->cover_image) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $lecture->title }}">
                @else
                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="bi bi-camera display-4 text-white"></i>
                    </div>
                @endif

                @if($lecture->status === 1)
                    <span class="position-absolute top-0 end-0 badge bg-danger m-2 live-badge">
                        <i class="bi bi-broadcast"></i> 直播中
                    </span>
                @endif

                <div class="card-body">
                    <h5 class="card-title">{{ $lecture->title }}</h5>
                    <p class="card-text text-muted">
                        <small>
                            @if($lecture->live_start_time)
                                <i class="bi bi-calendar"></i> {{ $lecture->live_start_time->format('Y-m-d H:i') }}
                            @endif
                        </small>
                    </p>
                    <p class="card-text">{{ Str::limit($lecture->description, 100) }}</p>

                    @if($lecture->status === 0 && $lecture->live_start_time && $lecture->live_start_time->isFuture())
                        <div class="countdown-small text-primary fw-bold" data-time="{{ $lecture->live_start_time->format('Y-m-d\TH:i:s') }}">
                            <i class="bi bi-clock"></i> 即将开始
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('lectures.show', $lecture) }}" class="btn btn-outline-primary w-100">
                        @if($lecture->status === 1)
                            进入直播间
                        @elseif($lecture->status === 0 && $lecture->live_start_time && $lecture->live_start_time->isFuture())
                            查看详情
                        @else
                            观看回顾
                        @endif
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-inbox display-1 text-muted"></i>
            <p class="mt-3 text-muted">暂无讲座信息</p>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $lectures->links() }}
    </div>
</div>
@endsection
