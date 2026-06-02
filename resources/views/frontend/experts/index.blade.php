@extends('layouts.app')

@section('title', '名家风采 - 多瑞吉医学名家讲堂')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-5">名家风采</h1>

    <div class="row">
        @forelse($experts as $expert)
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card card-hover text-center h-100">
                @if($expert->avatar)
                    <img src="{{ Storage::url($expert->avatar) }}" class="card-img-top rounded-circle mx-auto mt-4" style="width: 150px; height: 150px; object-fit: cover;" alt="{{ $expert->name }}">
                @else
                    <div class="mx-auto mt-4 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                        <i class="bi bi-person display-3 text-muted"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $expert->name }}</h5>
                    <p class="card-text text-muted">{{ $expert->title }}</p>
                    <p class="card-text"><small>{{ $expert->hospital }} {{ $expert->department }}</small></p>
                    <a href="{{ route('experts.show', $expert) }}" class="btn btn-outline-primary">查看详情</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-people display-1 text-muted"></i>
            <p class="mt-3 text-muted">暂无专家信息</p>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $experts->links() }}
    </div>
</div>
@endsection
