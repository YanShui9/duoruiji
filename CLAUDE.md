# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## 项目概述

多瑞吉医学名家讲堂 - 医学教育直播平台，支持讲座管理、专家管理、视频回放等功能。

## 技术栈

- **框架**: Laravel 8.x
- **PHP**: 7.4+
- **数据库**: MySQL
- **前端**: Bootstrap 5 + Blade 模板
- **测试**: PHPUnit

## 常用命令

```bash
# 启动开发服务器
php artisan serve

# 数据库迁移
php artisan migrate

# 重置数据库并填充测试数据
php artisan migrate:fresh --seed

# 运行全部测试
php artisan test

# 运行特定测试文件
php artisan test tests/Unit/Models/ExpertTest.php

# 运行特定测试用例
php artisan test --filter="ExpertTest"

# 清除缓存
php artisan config:clear
php artisan cache:clear
```

## 项目结构

```
duoruiji/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # 后台管理控制器
│   │   │   ├── Auth/           # 认证控制器
│   │   │   └── Frontend/       # 前台页面控制器
│   │   ├── Middleware/
│   │   │   └── AdminMiddleware.php  # 管理员权限验证
│   │   └── Requests/Admin/     # 表单验证
│   └── Models/                 # 数据模型
├── database/
│   ├── factories/              # 模型工厂（测试数据）
│   ├── migrations/             # 数据库迁移
│   └── seeders/                # 数据填充
├── resources/
│   ├── lang/zh/                # 中文语言包
│   └── views/
│       ├── admin/              # 后台管理视图
│       ├── frontend/           # 前台页面视图
│       └── vendor/pagination/  # 分页模板
└── tests/
    ├── Feature/Admin/          # 后台功能测试
    ├── Feature/Frontend/       # 前台功能测试
    ├── Feature/Auth/           # 认证测试
    └── Unit/Models/            # 模型单元测试
```

## 核心业务逻辑

### 讲座状态管理
- `status=0`: 未开始
- `status=1`: 直播中
- `status=2`: 已结束
- 状态自动更新逻辑在 `Lecture::updateExpiredStatuses()` 中

### 排序规则
- 所有列表按 ID 倒序排列（新内容在前）
- 讲座列表按状态分组：直播中 → 未开始 → 已结束

### 认证与权限
- 管理员用户 `is_admin=1`
- 后台路由需登录 + 管理员权限
- 登录后默认跳转 `/admin`

### 中文语言配置
- 已配置中文语言包：`resources/lang/zh/`
- 应用语言设置为 `zh`：`config/app.php`

## 测试数据

测试数据包含：
- 7 个专家（6个启用 + 1个禁用）
- 9 个讲座（3个即将开始 + 1个直播中 + 4个已结束）
- 11 个视频（10个启用 + 1个禁用）

管理员账号：`admin@test.com` / `123456`
