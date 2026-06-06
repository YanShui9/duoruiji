<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '后台管理') - 多瑞吉医学名家讲堂</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&family=Noto+Serif+SC:wght@400;500;600;700&family=Noto+Sans+SC:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_green.css" rel="stylesheet">
    <style>
        :root {
            /* Parsley Health 风格变量 */
            --color-primary: #1a3a2a;
            --color-primary-light: #2d5a3d;
            --color-sage: #8fa89a;
            --color-sage-light: #b5c9bc;
            --color-accent: #c4956a;
            --color-bg: #ffffff;
            --color-bg-warm: #f8f5f0;
            --color-bg-cream: #faf7f4;
            --color-text: #1a1a1a;
            --color-text-secondary: #666666;
            --color-text-light: #999999;
            --color-border: #e8e5e0;
            --color-success: #2d5a3d;
            --color-warning: #c4956a;
            --color-danger: #c0392b;
            --color-info: #2d5a3d;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 24px;
            --radius-pill: 999px;
            --shadow-subtle: 0 4px 24px rgba(0,0,0,0.06);
            --transition-base: 300ms ease;
        }

        * {
            font-family: 'Inter', 'Noto Sans SC', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background-color: var(--color-bg-warm);
            color: var(--color-text);
            min-height: 100vh;
            display: flex;
        }

        /* 侧边栏 */
        .admin-sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--color-primary);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all var(--transition-base);
            overflow-y: auto;
        }

        .admin-sidebar .sidebar-brand {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .admin-sidebar .sidebar-brand .brand-icon {
            width: 2.5rem;
            height: 2.5rem;
            background: rgba(255,255,255,0.15);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.1rem;
        }

        .admin-sidebar .sidebar-brand .brand-text {
            color: #fff;
        }

        .admin-sidebar .sidebar-brand .brand-text h5 {
            font-family: 'Noto Serif SC', serif;
            font-weight: 600;
            font-size: 1rem;
            margin: 0;
            color: #fff;
        }

        .admin-sidebar .sidebar-brand .brand-text small {
            font-size: 0.7rem;
            color: var(--color-sage-light);
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .admin-sidebar .sidebar-nav {
            padding: 1rem 0;
        }

        .admin-sidebar .sidebar-nav .nav-section {
            padding: 0 1.5rem;
            margin-bottom: 0.5rem;
        }

        .admin-sidebar .sidebar-nav .nav-section-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--color-sage);
            padding: 0.5rem 0;
            font-weight: 600;
        }

        .admin-sidebar .sidebar-nav .nav-item {
            padding: 0 0.75rem;
            margin-bottom: 2px;
        }

        .admin-sidebar .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--color-sage-light);
            text-decoration: none;
            border-radius: var(--radius-sm);
            transition: all var(--transition-base);
            font-size: 0.9rem;
            font-weight: 400;
        }

        .admin-sidebar .sidebar-nav .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        .admin-sidebar .sidebar-nav .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: #fff;
            font-weight: 500;
        }

        .admin-sidebar .sidebar-nav .nav-link i {
            font-size: 1.1rem;
            width: 1.5rem;
            text-align: center;
        }

        .admin-sidebar .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: auto;
        }

        .admin-sidebar .sidebar-footer .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .admin-sidebar .sidebar-footer .user-avatar {
            width: 2.5rem;
            height: 2.5rem;
            background: var(--color-accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .admin-sidebar .sidebar-footer .user-details h6 {
            color: #fff;
            margin: 0;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .admin-sidebar .sidebar-footer .user-details small {
            color: var(--color-sage);
            font-size: 0.75rem;
        }

        .admin-sidebar .sidebar-footer .btn-logout {
            width: 100%;
            padding: 0.625rem;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: var(--color-sage-light);
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            transition: all var(--transition-base);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .admin-sidebar .sidebar-footer .btn-logout:hover {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }

        /* 主内容区 */
        .admin-main {
            flex: 1;
            margin-left: 260px;
            min-height: 100vh;
        }

        /* 顶部栏 */
        .admin-topbar {
            background: var(--color-bg);
            border-bottom: 1px solid var(--color-border);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .admin-topbar .page-title {
            font-family: 'Noto Serif SC', serif;
            font-weight: 600;
            font-size: 1.25rem;
            color: var(--color-text);
            margin: 0;
        }

        .admin-topbar .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
            font-size: 0.85rem;
        }

        .admin-topbar .breadcrumb-item a {
            color: var(--color-sage);
            text-decoration: none;
        }

        .admin-topbar .breadcrumb-item.active {
            color: var(--color-text-secondary);
        }

        .admin-topbar .topbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-topbar .btn-visit {
            padding: 0.5rem 1rem;
            background: transparent;
            border: 1px solid var(--color-border);
            color: var(--color-text-secondary);
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            transition: all var(--transition-base);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .admin-topbar .btn-visit:hover {
            background: var(--color-bg-warm);
            border-color: var(--color-sage);
            color: var(--color-primary);
        }

        /* 内容区 */
        .admin-content {
            padding: 2rem;
        }

        /* 页面头部 */
        .page-header {
            margin-bottom: 1.5rem;
        }

        .page-header h2 {
            font-family: 'Noto Serif SC', serif;
            font-weight: 600;
            font-size: 1.5rem;
            color: var(--color-text);
            margin: 0 0 0.25rem;
        }

        .page-header .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
            font-size: 0.85rem;
        }

        .page-header .breadcrumb-item a {
            color: var(--color-sage);
            text-decoration: none;
        }

        .page-header .breadcrumb-item.active {
            color: var(--color-text-secondary);
        }

        /* 统计卡片 */
        .stat-card {
            background: var(--color-bg);
            border-radius: var(--radius-md);
            padding: 1.5rem;
            border: 1px solid var(--color-border);
            transition: all var(--transition-base);
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-subtle);
            border-color: var(--color-sage);
        }

        .stat-card .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .stat-card.primary .stat-icon {
            background: linear-gradient(135deg, var(--color-primary), var(--color-primary-light));
            color: #fff;
        }

        .stat-card.success .stat-icon {
            background: linear-gradient(135deg, var(--color-sage), var(--color-sage-light));
            color: #fff;
        }

        .stat-card.warning .stat-icon {
            background: linear-gradient(135deg, var(--color-accent), #d4a574);
            color: #fff;
        }

        .stat-card.danger .stat-icon {
            background: linear-gradient(135deg, var(--color-danger), #e74c3c);
            color: #fff;
        }

        .stat-card .stat-content h3 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            font-size: 2rem;
            margin: 0 0 0.25rem;
            color: var(--color-text);
        }

        .stat-card .stat-content p {
            margin: 0;
            color: var(--color-text-secondary);
            font-size: 0.875rem;
        }

        /* 表格卡片 */
        .table-card {
            background: var(--color-bg);
            border-radius: var(--radius-md);
            border: 1px solid var(--color-border);
            overflow: hidden;
        }

        .table-card .card-header {
            background: var(--color-bg);
            border-bottom: 1px solid var(--color-border);
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table-card .card-header h5 {
            font-family: 'Noto Serif SC', serif;
            font-weight: 600;
            margin: 0;
            color: var(--color-text);
            font-size: 1rem;
        }

        .table-card .card-body {
            padding: 0;
        }

        .table-card .table {
            margin: 0;
        }

        .table-card .table thead th {
            background: var(--color-bg-warm);
            border-bottom: 1px solid var(--color-border);
            font-weight: 600;
            color: var(--color-text-secondary);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.875rem 1.25rem;
        }

        .table-card .table tbody td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--color-border);
            font-size: 0.9rem;
        }

        .table-card .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table-card .table tbody tr:hover {
            background-color: var(--color-bg-warm);
        }

        /* 状态徽章 */
        .badge-status {
            padding: 0.35rem 0.875rem;
            border-radius: var(--radius-pill);
            font-weight: 500;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .badge-status.active {
            background: rgba(45, 90, 61, 0.1);
            color: var(--color-success);
        }

        .badge-status.inactive {
            background: rgba(153, 153, 153, 0.1);
            color: var(--color-text-light);
        }

        .badge-status.live {
            background: rgba(192, 57, 43, 0.1);
            color: var(--color-danger);
        }

        .badge-status.upcoming {
            background: rgba(196, 149, 106, 0.15);
            color: var(--color-accent);
        }

        .badge-status.ended {
            background: rgba(153, 153, 153, 0.1);
            color: var(--color-text-light);
        }

        /* 操作按钮 */
        .btn-action {
            padding: 0.375rem 0.75rem;
            border-radius: var(--radius-sm);
            font-size: 0.8rem;
            font-weight: 500;
            transition: all var(--transition-base);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .btn-action-edit {
            background: var(--color-bg-warm);
            color: var(--color-primary);
        }

        .btn-action-edit:hover {
            background: var(--color-primary);
            color: #fff;
        }

        .btn-action-delete {
            background: rgba(192, 57, 43, 0.1);
            color: var(--color-danger);
        }

        .btn-action-delete:hover {
            background: var(--color-danger);
            color: #fff;
        }

        .btn-action-view {
            background: rgba(45, 90, 61, 0.1);
            color: var(--color-success);
        }

        .btn-action-view:hover {
            background: var(--color-success);
            color: #fff;
        }

        /* 表单卡片 */
        .form-card {
            background: var(--color-bg);
            border-radius: var(--radius-md);
            border: 1px solid var(--color-border);
            overflow: hidden;
        }

        .form-card .card-header {
            background: var(--color-bg);
            border-bottom: 1px solid var(--color-border);
            padding: 1.25rem 1.5rem;
        }

        .form-card .card-header h5 {
            font-family: 'Noto Serif SC', serif;
            font-weight: 600;
            margin: 0;
            color: var(--color-text);
        }

        .form-card .card-body {
            padding: 1.5rem;
        }

        .form-card .form-label {
            font-weight: 500;
            color: var(--color-text);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-card .form-control,
        .form-card .form-select {
            border-radius: var(--radius-sm);
            border: 1px solid var(--color-border);
            padding: 0.75rem 1rem;
            transition: all var(--transition-base);
            font-size: 0.9rem;
        }

        .form-card .form-control:focus,
        .form-card .form-select:focus {
            border-color: var(--color-sage);
            box-shadow: 0 0 0 3px rgba(143, 168, 154, 0.15);
        }

        /* 按钮 */
        .btn-admin-primary {
            background: var(--color-primary);
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-sm);
            font-weight: 500;
            font-size: 0.9rem;
            transition: all var(--transition-base);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-admin-primary:hover {
            background: var(--color-primary-light);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 58, 42, 0.3);
        }

        .btn-admin-outline {
            background: transparent;
            border: 1px solid var(--color-border);
            color: var(--color-text);
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-sm);
            font-weight: 500;
            font-size: 0.9rem;
            transition: all var(--transition-base);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-admin-outline:hover {
            background: var(--color-bg-warm);
            border-color: var(--color-sage);
            color: var(--color-primary);
        }

        .btn-admin-accent {
            background: var(--color-accent);
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-sm);
            font-weight: 500;
            font-size: 0.9rem;
            transition: all var(--transition-base);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-admin-accent:hover {
            background: #d4a574;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(196, 149, 106, 0.3);
        }

        /* 提示框 */
        .alert {
            border-radius: var(--radius-sm);
            border: none;
            padding: 1rem 1.25rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: rgba(45, 90, 61, 0.1);
            color: var(--color-success);
            border-left: 4px solid var(--color-success);
        }

        .alert-danger {
            background: rgba(192, 57, 43, 0.1);
            color: var(--color-danger);
            border-left: 4px solid var(--color-danger);
        }

        .alert-warning {
            background: rgba(196, 149, 106, 0.15);
            color: var(--color-accent);
            border-left: 4px solid var(--color-accent);
        }

        /* 空状态 */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--color-sage-light);
            margin-bottom: 1.5rem;
            display: block;
        }

        .empty-state h5 {
            font-family: 'Noto Serif SC', serif;
            color: var(--color-text);
            margin-bottom: 0.75rem;
        }

        .empty-state p {
            color: var(--color-text-secondary);
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }

        /* 分页 */
        .pagination {
            margin: 1.5rem 0;
        }

        .pagination .page-link {
            border-radius: var(--radius-sm);
            margin: 0 2px;
            border: 1px solid var(--color-border);
            color: var(--color-text-secondary);
            padding: 0.5rem 0.875rem;
            font-size: 0.85rem;
            transition: all var(--transition-base);
        }

        .pagination .page-item.active .page-link {
            background: var(--color-primary);
            border-color: var(--color-primary);
            color: #fff;
        }

        .pagination .page-link:hover {
            background: var(--color-bg-warm);
            color: var(--color-primary);
            border-color: var(--color-sage);
        }

        /* 分页 - 与前台一致的简约优雅风格 */
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

        /* 图片预览 */
        .img-preview {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: var(--radius-sm);
            border: 1px solid var(--color-border);
        }

        .img-preview-sm {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--color-border);
        }

        .img-preview-lg {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: var(--radius-md);
            border: 1px solid var(--color-border);
        }

        /* 侧边栏切换按钮 */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--color-text);
            cursor: pointer;
            padding: 0.25rem;
        }

        /* 响应式 */
        @media (max-width: 992px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .admin-content {
                padding: 1rem;
            }

            .admin-topbar {
                padding: 1rem;
            }

            .stat-card {
                margin-bottom: 1rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- 侧边栏遮罩 -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- 侧边栏 -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="bi bi-heart-pulse"></i>
            </div>
            <div class="brand-text">
                <h5>多瑞吉讲堂</h5>
                <small>管理后台</small>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">概览</div>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-grid-1x2"></i>
                    <span>仪表盘</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">内容管理</div>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.lectures.*') ? 'active' : '' }}" href="{{ route('admin.lectures.index') }}">
                    <i class="bi bi-camera-video"></i>
                    <span>直播管理</span>
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.videos.*') ? 'active' : '' }}" href="{{ route('admin.videos.index') }}">
                    <i class="bi bi-play-circle"></i>
                    <span>录播管理</span>
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.experts.*') ? 'active' : '' }}" href="{{ route('admin.experts.index') }}">
                    <i class="bi bi-people"></i>
                    <span>专家管理</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">系统</div>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="bi bi-person-gear"></i>
                    <span>用户管理</span>
                </a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->thumb_avatar }}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                    @else
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    @endif
                </div>
                <div class="user-details">
                    <h6>{{ Auth::user()->name ?? '管理员' }}</h6>
                    <small>{{ Auth::user()->email ?? '' }}</small>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i>
                    退出登录
                </button>
            </form>
        </div>
    </aside>

    <!-- 主内容区 -->
    <main class="admin-main">
        <!-- 顶部栏 -->
        <div class="admin-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <h1 class="page-title">@yield('page-title', '仪表盘')</h1>
                    @hasSection('breadcrumb')
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    @endif
                </div>
            </div>
            <div class="topbar-actions">
                <a href="{{ route('home') }}" class="btn-visit" target="_blank">
                    <i class="bi bi-box-arrow-up-right"></i>
                    访问前台
                </a>
            </div>
        </div>

        <!-- 内容区 -->
        <div class="admin-content">
            <!-- 提示消息 -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ $errors->first() }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (Summernote 依赖) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
    <script>
        // 侧边栏切换
        const sidebarToggle = document.getElementById('sidebarToggle');
        const adminSidebar = document.getElementById('adminSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                adminSidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                adminSidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/zh.js"></script>
    <!-- Summernote 富文本编辑器 (免费，无需API密钥) -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/lang/summernote-zh-CN.min.js"></script>
    <!-- AJAX 文件上传 -->
    <script src="{{ asset('assets/js/ajax-upload.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $.fn.summernote !== 'undefined') {
            $('.rich-editor').summernote({
                lang: 'zh-CN',
                height: 300,
                placeholder: '请输入内容...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onImageUpload: function(files) {
                        for (var i = 0; i < files.length; i++) {
                            var formData = new FormData();
                            formData.append('image', files[i]);
                            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                            $.ajax({
                                url: '/admin/upload-image',
                                method: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(url) {
                                    $('.rich-editor').summernote('insertImage', url);
                                },
                                error: function() {
                                    alert('图片上传失败');
                                }
                            });
                        }
                    }
                }
            });
        }
    });
    </script>
    @stack('scripts')
</body>
</html>
