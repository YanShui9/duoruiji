@extends('layouts.admin')

@section('title', '添加用户')

@section('content')
<!-- 页面标题 -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2>添加用户</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">仪表盘</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">用户管理</a></li>
                <li class="breadcrumb-item active">添加用户</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-admin-outline">
        <i class="bi bi-arrow-left me-2"></i>返回列表
    </a>
</div>

<!-- 表单 -->
<div class="form-card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-person-plus me-2" style="color: var(--color-sage);"></i>用户信息</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    姓名 <span style="color: var(--color-danger);">*</span>
                                </label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}"
                                       placeholder="请输入姓名"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    邮箱 <span style="color: var(--color-danger);">*</span>
                                </label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}"
                                       placeholder="请输入邮箱"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    密码 <span style="color: var(--color-danger);">*</span>
                                </label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                       placeholder="请输入密码"
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small style="color: var(--color-text-light);">至少 8 个字符</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    确认密码 <span style="color: var(--color-danger);">*</span>
                                </label>
                                <input type="password" name="password_confirmation" class="form-control"
                                       placeholder="再次输入密码"
                                       required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">头像</label>
                        <div class="text-center p-4" style="background: var(--color-bg-warm); border-radius: var(--radius-md); border: 1px dashed var(--color-border);">
                            <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle"
                                 style="width: 150px; height: 150px; background: linear-gradient(135deg, var(--color-sage-light), var(--color-sage));">
                                <i class="bi bi-person text-white" style="font-size: 4rem;"></i>
                            </div>
                            <p style="color: var(--color-text-secondary); font-size: 0.85rem; margin-bottom: 0.5rem;">暂无头像</p>
                            <input type="file" name="avatar" id="avatarInput" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small style="color: var(--color-text-light); display: block; margin-top: 0.5rem;">支持 JPG、PNG、GIF，最大 2MB</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 mt-4 pt-4" style="border-top: 1px solid var(--color-border);">
                <button type="submit" class="btn btn-admin-primary">
                    <i class="bi bi-check-lg me-2"></i>保存
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-admin-outline">
                    <i class="bi bi-x-lg me-2"></i>取消
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var avatarInput = document.getElementById('avatarInput');
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (!file) return;
            if (!file.type.startsWith('image/')) return;

            var reader = new FileReader();
            reader.onload = function(ev) {
                var container = avatarInput.closest('.text-center');
                if (!container) return;

                var placeholder = container.querySelector('div[style*="linear-gradient"]');

                if (placeholder) {
                    var img = document.createElement('img');
                    img.src = ev.target.result;
                    img.alt = '头像预览';
                    img.className = 'rounded-circle mb-3';
                    img.style.cssText = 'width:150px;height:150px;object-fit:cover;box-shadow:0 4px 15px rgba(0,0,0,0.1);';
                    placeholder.parentNode.replaceChild(img, placeholder);

                    var hint = container.querySelector('p');
                    if (hint) hint.textContent = '头像预览';
                }
            };
            reader.readAsDataURL(file);
        });
    }
});
</script>
@endpush
