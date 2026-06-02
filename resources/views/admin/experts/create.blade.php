@extends('layouts.admin')

@section('title', '添加专家')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>添加专家</h2>
    <a href="{{ route('admin.experts.index') }}" class="btn btn-outline-secondary">返回列表</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.experts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.experts._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">保存</button>
            </div>
        </form>
    </div>
</div>
@endsection
