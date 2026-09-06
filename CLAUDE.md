# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## 项目概述

多瑞吉医学名家讲堂 - 医学教育直播平台，支持讲座管理、专家管理、视频回放等功能。

## 技术栈

- **框架**: Laravel 8.x (`laravel/framework ^8.75`)
- **PHP**: `^7.3|^8.0`
- **数据库**: MySQL 8.0
- **前端**: Bootstrap 5 + jQuery + Blade 模板
- **前端构建**: Laravel Mix (Webpack)
- **图片处理**: Intervention Image (`intervention/image ^2.7`)
- **测试**: PHPUnit 9.x
- **认证**: Laravel UI (`laravel/ui ^3.4`) + Sanctum

## 常用命令

```bash
# 启动开发服务器
php artisan serve

# 前端资源构建
npm run dev          # 开发构建
npm run prod         # 生产构建
npm run watch        # 监听文件变化自动构建

# 数据库
php artisan migrate                        # 运行迁移
php artisan migrate:fresh --seed           # 重置数据库并填充测试数据
php artisan db:seed                        # 仅填充数据

# 存储软链接（首次必须）
php artisan storage:link

# 测试
php artisan test                                    # 运行全部测试
php artisan test tests/Unit/Models/ExpertTest.php   # 运行特定测试文件
php artisan test --filter="ExpertTest"              # 运行特定测试用例

# 缓存（出现问题时先清缓存）
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Cloudflare Tunnel（本地公网访问）
./cloudflared.exe tunnel --url http://localhost:8000
```

## 项目结构

```
app/
├── Http/Controllers/
│   ├── Admin/              # 后台管理 CRUD（Expert/Lecture/Video/User/Dashboard）
│   ├── Auth/               # 认证控制器（Laravel UI 提供）
│   └── Frontend/           # 前台页面（Home/Expert/Lecture/Video）
├── Http/Middleware/
│   └── AdminMiddleware.php # 管理员权限验证（is_admin=1）
├── Http/Requests/Admin/    # 表单验证（ExpertRequest/LectureRequest/VideoRequest/UserRequest）
└── Models/                 # Expert, Lecture, Video, User
database/
├── factories/              # 模型工厂（测试数据生成）
├── migrations/             # 数据库迁移
└── seeders/                # 数据填充（ExpertSeeder/LectureSeeder/VideoSeeder/UserSeeder/TestDataSeeder）
resources/
├── lang/zh/                # 中文语言包
└── views/
    ├── admin/              # 后台管理视图（dashboard + experts/lectures/videos/users 子目录）
    ├── frontend/           # 前台视图（home + experts/lectures/videos 子目录）
    └── vendor/pagination/  # 自定义分页模板
tests/
├── Feature/Admin/          # 后台功能测试
├── Feature/Frontend/       # 前台功能测试
├── Feature/Auth/           # 认证测试
└── Unit/Models/            # 模型单元测试（ExpertTest/LectureTest/VideoTest）
```

## 路由结构

- **前台**: `/` (首页), `/lectures`, `/experts`, `/videos`, `/qrcode`
- **后台**: `/admin/*` — 需要 `auth` + `admin` 中间件
- 后台使用 `Route::resource`，路由名称前缀 `admin.`（如 `admin.experts.index`）

## 核心业务逻辑

### 讲座状态管理（Lecture model）
- `status=0`: 未开始
- `status=1`: 直播中
- `status=2`: 已结束
- 状态自动更新：`Lecture::updateExpiredStatuses()` 在页面加载时调用，根据 `live_start_time` / `live_end_time` 自动流转状态

### 讲座分类（Lecture categories）
- `cancer_pain` — 癌痛治疗
- `pain_management` — 疼痛管理
- `clinical_research` — 临床研究
- `academic_conference` — 学术会议

### 数据关系
- Lecture 和 Video 通过 `expert_ids`（JSON 数组）关联 Expert，**不是**标准的多对多 pivot 表
- `experts()` 方法使用 `Expert::whereIn('id', $this->expert_ids)` 查询，并有内存缓存

### 排序规则
- 所有列表按 ID 倒序（新内容在前）
- 讲座列表按状态分组排序：直播中 → 未开始 → 已结束（`scopeOrdered`）

### 认证与权限
- 管理员用户 `is_admin=1`（users 表字段）
- 后台路由需登录 + 管理员权限（`AdminMiddleware`）
- 登录后默认跳转 `/admin`

### 图片处理
- 使用 Intervention Image 生成缩略图
- 缩略图命名规则：原路径中目录名前加 `thumb_`（如 `/experts/avatar.jpg` → `/experts/thumb_avatar.jpg`）
- 存储于 `storage/app/public/`，通过 `php artisan storage:link` 访问

### Cloudflare Tunnel
- `cloudflared.exe` 位于项目根目录
- `.env` 中 `TUNNEL_URL` 配置后，分享链接会使用 tunnel 公网地址
- 用于本地开发时生成可外部访问的分享链接

## 测试数据

`php artisan db:seed` 填充内容：
- 7 个专家（6 启用 + 1 禁用）
- 9 个讲座（3 即将开始 + 1 直播中 + 4 已结束 + 1 分类示例）
- 11 个视频（10 启用 + 1 禁用）
- 管理员账号：`admin@test.com` / `123456`（UserSeeder）

## 开发注意事项

- 中文语言包已配置：`resources/lang/zh/`，应用语言设为 `zh`（`config/app.php`）
- `.env` 包含敏感信息，已在 `.gitignore` 中排除
- 表单验证在 `app/Http/Requests/Admin/` 中，后台控制器通过类型提示自动注入
