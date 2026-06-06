@extends('layouts.app')

@section('title', '登录 - 多瑞吉医学名家讲堂')

@section('content')
<section style="min-height: 100vh; display: flex; align-items: center; background: var(--color-bg-warm); padding: 2rem 0;">
    <div class="container" style="max-width: 1200px;">
        <div class="row align-items-center g-5">
            <!-- 左侧图片 -->
            <div class="col-lg-6 d-none d-lg-block fade-in-left">
                <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800&q=80"
                     alt="医学讲座"
                     style="width: 100%; border-radius: var(--radius-lg); box-shadow: 0 20px 60px rgba(0,0,0,0.1);">
            </div>

            <!-- 右侧登录表单 -->
            <div class="col-lg-6 col-md-8 mx-auto fade-in-right">
                <div style="background: var(--color-bg); padding: 3rem; border-radius: var(--radius-lg); box-shadow: 0 4px 24px rgba(0,0,0,0.06);">
                    <div class="text-center mb-4">
                        <h2 class="font-serif fw-semibold mb-2" style="font-size: 2rem;">欢迎回来</h2>
                        <p style="color: var(--color-text-secondary);">登录多瑞吉医学名家讲堂</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label" style="font-weight: 500; color: var(--color-text); font-size: 0.9rem;">
                                邮箱地址
                            </label>
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="请输入邮箱地址"
                                   required
                                   autocomplete="email"
                                   autofocus
                                   style="padding: 0.875rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border); font-size: 0.95rem;">
                            @error('email')
                                <div class="invalid-feedback" style="font-size: 0.85rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label" style="font-weight: 500; color: var(--color-text); font-size: 0.9rem;">
                                密码
                            </label>
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password"
                                   placeholder="请输入密码"
                                   required
                                   autocomplete="current-password"
                                   style="padding: 0.875rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border); font-size: 0.95rem;">
                            @error('password')
                                <div class="invalid-feedback" style="font-size: 0.85rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember" style="color: var(--color-text-secondary); font-size: 0.9rem;">
                                    记住我
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn-parsley btn-parsley-dark w-100" style="padding: 0.875rem; font-size: 1rem; justify-content: center;">
                            登录
                        </button>
                    </form>
                </div>

                <div class="text-center mt-4">
                    <p style="color: var(--color-text-light); font-size: 0.85rem;">
                        &copy; {{ date('Y') }} 多瑞吉医学名家讲堂
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
