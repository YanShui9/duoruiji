@extends('layouts.admin')

@section('title', '编辑讲座')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>编辑讲座</h2>
    <a href="{{ route('admin.lectures.index') }}" class="btn btn-outline-secondary">返回列表</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.lectures.update', $lecture) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.lectures._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">更新</button>
            </div>
        </form>
    </div>
</div>
@endsection
