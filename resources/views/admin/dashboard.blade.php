@extends('layouts.admin')

@section('title', '仪表盘')

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">专家总数</h5>
                <h2>{{ $stats['experts'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">讲座总数</h5>
                <h2>{{ $stats['lectures'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">视频总数</h5>
                <h2>{{ $stats['videos'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h5 class="card-title">正在直播</h5>
                <h2>{{ $stats['live_now'] }}</h2>
            </div>
        </div>
    </div>
</div>
@endsection
