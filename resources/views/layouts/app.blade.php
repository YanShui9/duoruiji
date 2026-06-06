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
    <!-- 衬线字体 + 无衬线字体 -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Inter:wght@300;400;500;600&family=Noto+Serif+SC:wght@400;500;600;700&family=Noto+Sans+SC:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Parsley Health 风格变量 */
        :root {
            /* 自然绿色系 */
            --color-primary: #1a3a2a;
            --color-primary-light: #2d5a3d;
            --color-sage: #8fa89a;
            --color-sage-light: #b5c9bc;

            /* 暖色系 */
            --color-accent: #c4956a;
            --color-accent-light: #d4a87a;

            /* 背景色 */
            --color-bg: #ffffff;
            --color-bg-warm: #f8f5f0;
            --color-bg-cream: #faf7f4;

            /* 文字色 */
            --color-text: #1a1a1a;
            --color-text-secondary: #666666;
            --color-text-light: #999999;

            /* 间距 */
            --space-unit: 8px;
            --section-padding: 120px;

            /* 圆角 */
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 24px;
            --radius-pill: 999px;

            /* 阴影 */
            --shadow-subtle: 0 4px 24px rgba(0,0,0,0.06);

            /* 动画 */
            --transition-base: 300ms ease;
            --animation-fade-in: 800ms ease-out;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', 'Noto Sans SC', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--color-text);
            background-color: var(--color-bg);
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* 衬线字体 */
        .font-serif {
            font-family: 'Playfair Display', 'Noto Serif SC', Georgia, serif;
        }

        /* 导航栏 - 半透明毛玻璃 */
        .navbar-parsley {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 1.25rem 0;
            transition: all var(--transition-base);
            background: transparent;
        }

        .navbar-parsley.scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 0.75rem 0;
            box-shadow: 0 1px 0 rgba(0,0,0,0.05);
        }

        .navbar-parsley .navbar-brand {
            font-family: 'Playfair Display', 'Noto Serif SC', serif;
            font-weight: 600;
            font-size: 1.25rem;
            color: var(--color-primary);
            transition: color var(--transition-base);
        }

        .navbar-parsley:not(.scrolled) .navbar-brand {
            color: #fff;
        }

        .navbar-parsley .nav-link {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--color-text-secondary);
            padding: 0.5rem 1rem;
            transition: color var(--transition-base);
            position: relative;
        }

        .navbar-parsley:not(.scrolled) .nav-link {
            color: rgba(255,255,255,0.85);
        }

        .navbar-parsley .nav-link:hover {
            color: var(--color-primary);
        }

        .navbar-parsley:not(.scrolled) .nav-link:hover {
            color: #fff;
        }

        .navbar-parsley .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 1rem;
            right: 1rem;
            height: 1.5px;
            background: var(--color-primary);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform var(--transition-base);
        }

        .navbar-parsley .nav-link:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        .navbar-parsley:not(.scrolled) .nav-link::after {
            background: #fff;
        }

        /* Hero 区域 */
        .hero-section {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
        }

        .hero-bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                135deg,
                rgba(26, 58, 42, 0.85) 0%,
                rgba(26, 58, 42, 0.6) 50%,
                rgba(26, 58, 42, 0.4) 100%
            );
        }

        .hero-content {
            position: relative;
            z-index: 1;
            color: #fff;
            max-width: 700px;
        }

        .hero-content h1 {
            font-family: 'Playfair Display', 'Noto Serif SC', serif;
            font-size: 4rem;
            font-weight: 600;
            line-height: 1.15;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
        }

        .hero-content p {
            font-size: 1.25rem;
            line-height: 1.8;
            opacity: 0.9;
            margin-bottom: 2.5rem;
        }

        /* 按钮样式 */
        .btn-parsley {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            border-radius: var(--radius-pill);
            font-size: 0.95rem;
            font-weight: 500;
            text-decoration: none;
            transition: all var(--transition-base);
            border: none;
            cursor: pointer;
        }

        .btn-parsley-primary {
            background: var(--color-accent);
            color: #fff;
        }

        .btn-parsley-primary:hover {
            background: var(--color-accent-light);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(196, 149, 106, 0.3);
        }

        .btn-parsley-outline {
            background: transparent;
            border: 1.5px solid rgba(255,255,255,0.4);
            color: #fff;
        }

        .btn-parsley-outline:hover {
            background: rgba(255,255,255,0.15);
            border-color: rgba(255,255,255,0.6);
            color: #fff;
        }

        .btn-parsley-dark {
            background: var(--color-primary);
            color: #fff;
        }

        .btn-parsley-dark:hover {
            background: var(--color-primary-light);
            color: #fff;
            transform: translateY(-2px);
        }

        .btn-parsley-ghost {
            background: transparent;
            color: var(--color-primary);
            padding: 0.75rem 0;
            font-weight: 500;
        }

        .btn-parsley-ghost:hover {
            color: var(--color-primary-light);
        }

        .btn-parsley-ghost i {
            transition: transform var(--transition-base);
        }

        .btn-parsley-ghost:hover i {
            transform: translateX(4px);
        }

        /* 返回按钮 */
        .btn-back {
            background: var(--color-bg);
            border: 1px solid var(--color-border);
            color: var(--color-text-secondary);
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            padding: 0.4rem 1rem;
            transition: all var(--transition-base);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .btn-back:hover {
            background: var(--color-primary);
            border-color: var(--color-primary);
            color: #fff;
            transform: translateX(-4px);
        }

        .btn-back i {
            transition: transform var(--transition-base);
        }

        .btn-back:hover i {
            transform: translateX(-3px);
        }

        .btn-back-hero {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            color: #fff;
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            padding: 0.5rem 1.25rem;
            transition: all var(--transition-base);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .btn-back-hero:hover {
            background: rgba(255,255,255,0.25);
            border-color: rgba(255,255,255,0.5);
            color: #fff;
            transform: translateX(-4px);
        }

        .btn-back-hero i {
            transition: transform var(--transition-base);
        }

        .btn-back-hero:hover i {
            transform: translateX(-3px);
        }

        /* 区域标题 */
        .section-header {
            margin-bottom: 4rem;
        }

        .section-header .section-label {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--color-sage);
            margin-bottom: 1rem;
        }

        .section-header h2 {
            font-family: 'Playfair Display', 'Noto Serif SC', serif;
            font-size: 2.75rem;
            font-weight: 600;
            color: var(--color-text);
            line-height: 1.2;
            margin-bottom: 1.25rem;
        }

        .section-header p {
            font-size: 1.125rem;
            color: var(--color-text-secondary);
            line-height: 1.8;
            max-width: 560px;
        }

        .section-header.text-center p {
            margin: 0 auto;
        }

        /* 统计数字 */
        .stat-item {
            text-align: center;
            padding: 2rem;
        }

        .stat-number {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 600;
            color: var(--color-primary);
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--color-text-secondary);
            font-weight: 500;
        }

        /* 专家卡片 */
        .expert-card {
            text-align: center;
            padding: 2.5rem 1.5rem;
            background: var(--color-bg);
            border-radius: var(--radius-md);
            transition: all var(--transition-base);
        }

        .expert-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-subtle);
        }

        .expert-avatar-wrapper {
            width: 140px;
            height: 140px;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            overflow: hidden;
            position: relative;
        }

        .expert-avatar-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition-base);
        }

        .expert-card:hover .expert-avatar-wrapper img {
            transform: scale(1.05);
        }

        .expert-avatar-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--color-sage-light), var(--color-sage));
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .expert-avatar-placeholder i {
            font-size: 3rem;
            color: #fff;
        }

        .expert-name {
            font-family: 'Playfair Display', 'Noto Serif SC', serif;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--color-text);
        }

        .expert-title {
            font-size: 0.875rem;
            color: var(--color-text-secondary);
            margin-bottom: 0.5rem;
        }

        .expert-hospital {
            font-size: 0.8rem;
            color: var(--color-text-light);
        }

        /* 讲座卡片 */
        .lecture-card {
            background: var(--color-bg);
            border-radius: var(--radius-md);
            overflow: hidden;
            transition: all var(--transition-base);
            display: flex;
            flex-direction: column;
        }

        .lecture-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-subtle);
        }

        .lecture-card-img {
            position: relative;
            overflow: hidden;
            aspect-ratio: 16/10;
        }

        .lecture-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .lecture-card:hover .lecture-card-img img {
            transform: scale(1.06);
        }

        .lecture-card-img .badge-status {
            position: absolute;
            top: 1rem;
            left: 1rem;
            padding: 0.4rem 1rem;
            border-radius: var(--radius-pill);
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-live {
            background: rgba(255,255,255,0.15);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.25);
        }

        .badge-upcoming {
            background: var(--color-accent);
            color: #fff;
        }

        .badge-ended {
            background: rgba(255,255,255,0.9);
            color: var(--color-text-secondary);
        }

        /* 直播轮播样式 - 优雅简约风格 */
        .live-carousel-wrapper {
            position: relative;
            margin: 0 -2rem;
            padding: 0 2rem;
        }

        #liveCarousel {
            position: relative;
            overflow: visible;
        }

        .live-carousel-content {
            text-align: center;
            padding: 1rem 6rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .live-carousel-title {
            font-family: 'Noto Serif SC', serif;
            font-weight: 600;
            font-size: 2.75rem;
            margin-bottom: 1rem;
            line-height: 1.35;
            letter-spacing: -0.01em;
        }

        .live-carousel-subtitle {
            font-size: 1.2rem;
            opacity: 0.85;
            margin-bottom: 0;
            font-weight: 300;
            letter-spacing: 0.03em;
        }

        .live-carousel-subtitle i {
            opacity: 0.6;
        }

        .btn-live-enter {
            padding: 0.875rem 2.5rem;
            font-size: 1rem;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        /* 直播状态徽章 - 柔和风格 */
        .live-badge-wrapper {
            display: flex;
            justify-content: center;
        }

        .live-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.18);
            padding: 0.625rem 1.5rem;
            border-radius: 100px;
            color: rgba(255,255,255,0.95);
            font-size: 0.9rem;
            font-weight: 400;
            letter-spacing: 0.03em;
        }

        .live-dot {
            width: 8px;
            height: 8px;
            background: #ff6b6b;
            border-radius: 50%;
            animation: livePulse 2s ease-in-out infinite;
            box-shadow: 0 0 0 0 rgba(255,107,107,0.4);
        }

        @keyframes livePulse {
            0%, 100% {
                opacity: 1;
                box-shadow: 0 0 0 0 rgba(255,107,107,0.4);
            }
            50% {
                opacity: 0.8;
                box-shadow: 0 0 0 6px rgba(255,107,107,0);
            }
        }

        .live-count {
            background: rgba(255,255,255,0.2);
            padding: 0.2rem 0.6rem;
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* 左右箭头 - 简约线条风格 */
        .carousel-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 64px;
            height: 64px;
            background: none;
            border: none;
            cursor: pointer;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            transition: all 0.3s ease;
        }

        .carousel-arrow svg {
            width: 32px;
            height: 32px;
            stroke: rgba(255,255,255,0.35);
            stroke-width: 1.5;
            fill: none;
            transition: all 0.3s ease;
        }

        .carousel-arrow:hover svg {
            stroke: #fff;
            stroke-width: 2;
            filter: drop-shadow(0 2px 12px rgba(255,255,255,0.4));
        }

        .carousel-arrow:active {
            transform: translateY(-50%) scale(0.9);
        }

        .carousel-arrow-prev {
            left: -2rem;
        }

        .carousel-arrow-next {
            right: -2rem;
        }

        /* 指示器 - 小圆点风格 */
        .carousel-dots {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 2rem;
        }

        .carousel-dot {
            width: 8px;
            height: 8px;
            border: none;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            cursor: pointer;
            padding: 0;
            transition: all 0.25s ease;
        }

        .carousel-dot:hover {
            background: rgba(255,255,255,0.6);
            transform: scale(1.2);
        }

        .carousel-dot.active {
            background: #fff;
            width: 24px;
            border-radius: 4px;
            transform: scale(1);
        }

        /* 轮播项动画 */
        #liveCarousel .carousel-item {
            transition: opacity 0.4s ease;
        }

        #liveCarousel .carousel-item.active .live-carousel-content {
            animation: liveFadeIn 0.4s ease-out;
        }

        @keyframes liveFadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #experts-grid {
            transition: opacity 0.4s ease;
        }

        /* 前台分页样式 - 简约优雅风格 */
        .frontend-pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .frontend-pagination .pagination-nav {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .frontend-pagination .page-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-sm);
            background: var(--color-bg);
            color: var(--color-text-secondary);
            font-size: 0.9rem;
            font-weight: 400;
            text-decoration: none;
            transition: all var(--transition-base);
            cursor: pointer;
        }

        .frontend-pagination .page-btn:hover {
            background: var(--color-bg-warm);
            border-color: var(--color-sage);
            color: var(--color-primary);
        }

        .frontend-pagination .page-btn.active {
            background: var(--color-primary);
            border-color: var(--color-primary);
            color: #fff;
            font-weight: 500;
        }

        .frontend-pagination .page-btn.disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }

        .frontend-pagination .page-ellipsis {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            color: var(--color-text-light);
            font-size: 0.9rem;
        }

        .frontend-pagination .page-info {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--color-text-secondary);
            font-size: 0.85rem;
        }

        .frontend-pagination .page-jump {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .frontend-pagination .page-jump input {
            width: 56px;
            height: 36px;
            padding: 0 8px;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            text-align: center;
            transition: all var(--transition-base);
        }

        .frontend-pagination .page-jump input:focus {
            outline: none;
            border-color: var(--color-sage);
            box-shadow: 0 0 0 3px rgba(143, 168, 154, 0.15);
        }

        .frontend-pagination .page-jump button {
            height: 36px;
            padding: 0 14px;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-sm);
            background: var(--color-bg);
            color: var(--color-text-secondary);
            font-size: 0.85rem;
            cursor: pointer;
            transition: all var(--transition-base);
        }

        .frontend-pagination .page-jump button:hover {
            background: var(--color-bg-warm);
            border-color: var(--color-sage);
            color: var(--color-primary);
        }

        /* 直播状态颜色 - 柔和风格 */
        .live-color {
            color: #e8706a;
        }

        .live-bg {
            background: #e8706a;
        }

        .live-bg-light {
            background: rgba(232, 112, 106, 0.1);
        }

        /* 响应式 */
        @media (max-width: 992px) {
            .live-carousel-title {
                font-size: 2.25rem;
            }

            .live-carousel-content {
                padding: 1rem 5rem;
            }

            .carousel-arrow {
                width: 56px;
                height: 56px;
            }

            .carousel-arrow svg {
                width: 28px;
                height: 28px;
            }
        }

        @media (max-width: 768px) {
            .live-carousel-wrapper {
                margin: 0 -1rem;
                padding: 0 1rem;
            }

            .live-carousel-title {
                font-size: 1.875rem;
            }

            .live-carousel-subtitle {
                font-size: 1.05rem;
            }

            .live-carousel-content {
                padding: 1rem 4rem;
            }

            .carousel-arrow {
                width: 48px;
                height: 48px;
            }

            .carousel-arrow svg {
                width: 24px;
                height: 24px;
            }

            .carousel-arrow-prev {
                left: -1rem;
            }

            .carousel-arrow-next {
                right: -1rem;
            }

            .btn-live-enter {
                padding: 0.75rem 2rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .live-carousel-title {
                font-size: 1.625rem;
            }

            .live-carousel-content {
                padding: 0.5rem 3rem;
            }

            .carousel-arrow {
                width: 40px;
                height: 40px;
            }

            .carousel-arrow svg {
                width: 20px;
                height: 20px;
            }
        }

        .lecture-card-body {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .lecture-card-body .btn-parsley {
            margin-top: auto;
        }

        .lecture-card-title {
            font-family: 'Playfair Display', 'Noto Serif SC', serif;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--color-text);
            line-height: 1.4;
        }

        .lecture-card-meta {
            font-size: 0.85rem;
            color: var(--color-text-light);
            margin-bottom: 1rem;
        }

        .lecture-card-desc {
            font-size: 0.9rem;
            color: var(--color-text-secondary);
            line-height: 1.7;
            margin-bottom: 1.25rem;
        }

        /* 特色区块 */
        .feature-block {
            display: flex;
            align-items: center;
            gap: 4rem;
            margin-bottom: 6rem;
        }

        .feature-block:nth-child(even) {
            flex-direction: row-reverse;
        }

        .feature-block-img {
            flex: 1;
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .feature-block-img img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.6s ease;
        }

        .feature-block-img:hover img {
            transform: scale(1.03);
        }

        .feature-block-content {
            flex: 1;
        }

        .feature-icon {
            width: 3.5rem;
            height: 3.5rem;
            background: var(--color-bg-warm);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: var(--color-primary);
            font-size: 1.5rem;
        }

        .feature-title {
            font-family: 'Playfair Display', 'Noto Serif SC', serif;
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--color-text);
        }

        .feature-description {
            font-size: 1.05rem;
            color: var(--color-text-secondary);
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }

        /* 倒计时 */
        .countdown-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: var(--radius-lg);
            padding: 2.5rem;
            display: inline-block;
        }

        .countdown-item {
            text-align: center;
            padding: 0 1.75rem;
        }

        .countdown-number {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 600;
            color: #fff;
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }

        .countdown-label {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.7);
            margin-top: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            font-weight: 500;
        }

        /* CTA 区域 */
        .cta-section {
            background: var(--color-primary);
            color: #fff;
            padding: 6rem 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 50%, rgba(143, 168, 154, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 80% 50%, rgba(196, 149, 106, 0.15) 0%, transparent 50%);
            pointer-events: none;
        }

        .cta-section h2 {
            font-family: 'Playfair Display', 'Noto Serif SC', serif;
            font-size: 2.75rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            position: relative;
        }

        .cta-section p {
            font-size: 1.125rem;
            opacity: 0.85;
            margin-bottom: 2.5rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            position: relative;
        }

        /* 页脚 */
        .footer-parsley {
            background: var(--color-bg-warm);
            padding: 5rem 0 2rem;
        }

        .footer-parsley h5 {
            font-family: 'Playfair Display', 'Noto Serif SC', serif;
            font-weight: 600;
            margin-bottom: 1.25rem;
            font-size: 1rem;
            color: var(--color-text);
        }

        .footer-parsley .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-parsley .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-parsley .footer-links a {
            color: var(--color-text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color var(--transition-base);
        }

        .footer-parsley .footer-links a:hover {
            color: var(--color-primary);
        }

        .footer-contact {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            color: var(--color-text-secondary);
            font-size: 0.9rem;
        }

        .footer-contact i {
            color: var(--color-sage);
            font-size: 1rem;
            width: 1rem;
        }

        .footer-bottom {
            border-top: 1px solid rgba(0,0,0,0.06);
            padding-top: 2rem;
            margin-top: 3rem;
        }

        /* 滚动动画 */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity var(--animation-fade-in), transform var(--animation-fade-in);
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .fade-in-left {
            opacity: 0;
            transform: translateX(-40px);
            transition: opacity var(--animation-fade-in), transform var(--animation-fade-in);
        }

        .fade-in-left.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .fade-in-right {
            opacity: 0;
            transform: translateX(40px);
            transition: opacity var(--animation-fade-in), transform var(--animation-fade-in);
        }

        .fade-in-right.visible {
            opacity: 1;
            transform: translateX(0);
        }

        /* 响应式 */
        @media (max-width: 992px) {
            .hero-content h1 {
                font-size: 3rem;
            }

            .feature-block {
                flex-direction: column !important;
                gap: 2.5rem;
            }

            .section-header h2 {
                font-size: 2.25rem;
            }

            .stat-number {
                font-size: 2.75rem;
            }
        }

        @media (max-width: 768px) {
            :root {
                --section-padding: 80px;
            }

            .hero-content h1 {
                font-size: 2.5rem;
            }

            .hero-content p {
                font-size: 1.1rem;
            }

            .countdown-number {
                font-size: 2.5rem;
            }

            .countdown-item {
                padding: 0 1rem;
            }

            .section-header h2 {
                font-size: 2rem;
            }

            .feature-title {
                font-size: 1.5rem;
            }

            .cta-section h2 {
                font-size: 2rem;
            }
        }

        /* 选中样式 */
        ::selection {
            background: rgba(143, 168, 154, 0.3);
            color: var(--color-text);
        }

        /* 滚动条 */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--color-bg-warm);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--color-sage-light);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--color-sage);
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- 导航栏 -->
    <nav class="navbar navbar-expand-lg navbar-parsley" id="mainNav">
        <div class="container" style="max-width: 1200px;">
            <a class="navbar-brand" href="{{ route('home') }}">
                多瑞吉名家讲堂
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list" style="font-size: 1.5rem; color: var(--color-text);"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-2">
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

    <!-- 主内容 -->
    <main>
        @yield('content')
    </main>

    <!-- 页脚 -->
    <footer class="footer-parsley">
        <div class="container" style="max-width: 1200px;">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="d-flex align-items-center gap-2">
                        <i class="bi bi-heart-pulse" style="color: var(--color-sage);"></i>
                        多瑞吉名家讲堂
                    </h5>
                    <p style="color: var(--color-text-secondary); line-height: 1.8; font-size: 0.9rem;">
                        致力于医学学术交流与技术普及，邀请国内外名医、教授开设专题在线讲座，促进医学知识传播与交流。
                    </p>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5>快速链接</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">首页</a></li>
                        <li><a href="{{ route('lectures.index') }}">名家讲堂</a></li>
                        <li><a href="{{ route('experts.index') }}">名家风采</a></li>
                        <li><a href="{{ route('videos.index') }}">往期视频</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <h5>讲座分类</h5>
                    <ul class="footer-links">
                        @foreach(\App\Models\Lecture::getCategories() as $key => $name)
                            <li><a href="{{ route('lectures.index', ['category' => $key]) }}">{{ $name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <h5>联系我们</h5>
                    <div class="footer-contact">
                        <i class="bi bi-building"></i>
                        <span>西安杨森制药有限公司</span>
                    </div>
                    <div class="footer-contact">
                        <i class="bi bi-telephone"></i>
                        <span>400-xxx-xxxx</span>
                    </div>
                    <div class="footer-contact">
                        <i class="bi bi-envelope"></i>
                        <span>contact@duoruiji.com</span>
                    </div>
                </div>
            </div>
            <div class="footer-bottom text-center">
                <p style="color: var(--color-text-light); margin: 0; font-size: 0.85rem;">
                    &copy; {{ date('Y') }} 多瑞吉医学名家讲堂. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery + BlockUI (用于遮罩层) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-blockui@2.70/jquery.blockUI.min.js"></script>
    <script>
        // 导航栏滚动效果
        const navbar = document.getElementById('mainNav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // 滚动动画
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in, .fade-in-left, .fade-in-right').forEach(el => {
            observer.observe(el);
        });
    </script>
    @stack('scripts')
</body>
</html>
