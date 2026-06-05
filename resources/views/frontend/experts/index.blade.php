@extends('layouts.app')

@section('title', '名家风采 - 多瑞吉医学名家讲堂')

@section('content')
<!-- 页面头部 - 全屏图片 -->
<section class="hero-section" style="min-height: 50vh;">
    <div class="hero-bg">
        <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=1920&q=80"
             alt="名家风采"
             loading="eager">
    </div>
    <div class="hero-overlay" style="background: linear-gradient(135deg, rgba(26, 58, 42, 0.9) 0%, rgba(26, 58, 42, 0.7) 100%);"></div>
    <div class="container" style="max-width: 1200px;">
        <div class="hero-content text-center" style="max-width: 100%;">
            <div class="section-label mb-3" style="color: rgba(255,255,255,0.7);">OUR EXPERTS</div>
            <h1 style="font-size: 3.5rem;">名家风采</h1>
            <p style="font-size: 1.25rem; opacity: 0.9; max-width: 500px; margin: 0 auto;">
                汇聚国内顶尖医学专家，分享前沿学术成果
            </p>
            <a href="{{ route('home') }}" class="btn-back-hero mt-3">
                <i class="bi bi-arrow-left"></i> 返回首页
            </a>
        </div>
    </div>
</section>

<!-- 筛选栏 -->
<section style="padding: 2rem 0 0;">
    <div class="container" style="max-width: 1200px;">
        <form action="{{ route('experts.index') }}" method="GET">
            <div style="background: var(--color-bg-warm); border-radius: var(--radius-md); padding: 1.5rem;">
                <div class="d-flex gap-3 flex-wrap align-items-center">
                    <!-- 搜索框 -->
                    <div style="flex: 1; min-width: 200px; position: relative;">
                        <i class="bi bi-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--color-text-light);"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="搜索专家姓名..."
                               style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 1px solid var(--color-border); border-radius: var(--radius-pill); background: white;">
                    </div>
                    <!-- 医院下拉 -->
                    <select name="hospital" style="padding: 0.75rem 1rem; border: 1px solid var(--color-border); border-radius: var(--radius-pill); background: white; min-width: 150px;">
                        <option value="">全部医院</option>
                        @foreach($hospitals as $h)
                            <option value="{{ $h }}" {{ request('hospital') == $h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                    <!-- 科室下拉 -->
                    <select name="department" style="padding: 0.75rem 1rem; border: 1px solid var(--color-border); border-radius: var(--radius-pill); background: white; min-width: 120px;">
                        <option value="">全部科室</option>
                        @foreach($departments as $d)
                            <option value="{{ $d }}" {{ request('department') == $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                    <!-- 搜索按钮 -->
                    <button type="submit" class="btn-parsley btn-parsley-dark">
                        <i class="bi bi-search"></i> 搜索
                    </button>
                    <!-- 重置按钮 -->
                    <a href="{{ route('experts.index') }}" class="btn-parsley btn-parsley-outline" style="border-color: var(--color-border); color: var(--color-text-secondary);">
                        重置
                    </a>
                </div>
                <!-- 已选条件 -->
                @if(request('search') || request('hospital') || request('department'))
                <div class="mt-3 d-flex gap-2 align-items-center">
                    <span style="font-size: 0.85rem; color: var(--color-text-light);">已选：</span>
                    @if(request('search'))
                        <span style="background: var(--color-primary); color: white; padding: 0.25rem 0.75rem; border-radius: var(--radius-pill); font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                            {{ request('search') }} <a href="{{ route('experts.index', array_merge(request()->except('search'))) }}" style="color: white; text-decoration: none;">×</a>
                        </span>
                    @endif
                    @if(request('hospital'))
                        <span style="background: var(--color-primary); color: white; padding: 0.25rem 0.75rem; border-radius: var(--radius-pill); font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                            {{ request('hospital') }} <a href="{{ route('experts.index', array_merge(request()->except('hospital'))) }}" style="color: white; text-decoration: none;">×</a>
                        </span>
                    @endif
                    @if(request('department'))
                        <span style="background: var(--color-primary); color: white; padding: 0.25rem 0.75rem; border-radius: var(--radius-pill); font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                            {{ request('department') }} <a href="{{ route('experts.index', array_merge(request()->except('department'))) }}" style="color: white; text-decoration: none;">×</a>
                        </span>
                    @endif
                </div>
                @endif
            </div>
        </form>
    </div>
</section>

<!-- 专家列表 -->
<section style="padding: var(--section-padding) 0;">
    <div class="container" style="max-width: 1200px;">
        <div class="row g-4 justify-content-center">
            @forelse($experts as $index => $expert)
            <div class="col-lg-3 col-md-4 col-sm-6 fade-in" style="transition-delay: {{ ($index % 4) * 100 }}ms;">
                <div class="expert-card">
                    <div class="expert-avatar-wrapper">
                        @if($expert->avatar)
                            <img src="{{ $expert->thumb_avatar }}" alt="{{ $expert->name }}">
                        @else
                            <div class="expert-avatar-placeholder">
                                <i class="bi bi-person"></i>
                            </div>
                        @endif
                    </div>
                    <div class="expert-name">{{ $expert->name }}</div>
                    <div class="expert-title">{{ $expert->title }}</div>
                    <div class="expert-hospital">
                        <i class="bi bi-hospital me-1"></i>
                        {{ $expert->hospital }}
                    </div>
                    <div style="font-size: 0.8rem; color: var(--color-text-light); margin-bottom: 1rem;">
                        <i class="bi bi-geo-alt me-1"></i>
                        {{ $expert->department }}
                    </div>
                    <a href="{{ route('experts.show', $expert) }}" class="btn-parsley btn-parsley-ghost">
                        查看详情 <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="empty-state" style="padding: 6rem 2rem;">
                    <i class="bi bi-people d-block" style="font-size: 3rem; color: var(--color-sage-light); margin-bottom: 1.5rem;"></i>
                    <p style="color: var(--color-text-secondary); font-size: 1.1rem;">暂无专家信息</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- 分页 -->
        @if($experts->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $experts->links() }}
        </div>
        @endif
    </div>
</section>
@endsection
