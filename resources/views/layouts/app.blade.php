<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '多瑞吉医学名家讲堂')</title>
    <meta name="description" content="@yield('description', '多瑞吉医学名家讲堂，邀请国内外名医、教授开设专题在线讲座')">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .live-badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <i class="bi bi-heart-pulse text-danger"></i> 多瑞吉名家讲堂
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">首页</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('lectures.*') ? 'active' : '' }}" href="{{ route('lectures.index') }}">名家讲堂</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('experts.*') ? 'active' : '' }}" href="{{ route('experts.index') }}">名家风采</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('videos.*') ? 'active' : '' }}" href="{{ route('videos.index') }}">往期视频</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>关于我们</h5>
                    <p class="text-muted">多瑞吉医学名家讲堂，致力于医学学术交流与技术普及。</p>
                </div>
                <div class="col-md-4">
                    <h5>快速链接</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-muted">首页</a></li>
                        <li><a href="{{ route('lectures.index') }}" class="text-muted">名家讲堂</a></li>
                        <li><a href="{{ route('experts.index') }}" class="text-muted">名家风采</a></li>
                        <li><a href="{{ route('videos.index') }}" class="text-muted">往期视频</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>联系方式</h5>
                    <p class="text-muted">西安杨森制药有限公司</p>
                    <p class="text-muted">客服电话：400-xxx-xxxx</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center text-muted">
                <p>&copy; {{ date('Y') }} 多瑞吉医学名家讲堂. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
