# 多瑞吉医学名家讲堂

## 项目简介

多瑞吉医学名家讲堂是一个在线医学学术交流平台，旨在邀请国内外名医、教授开设专题在线讲座，进行相关领域学术及技术普及和沟通。

## 主要功能

- **首页**：直播预告、本期专家、精彩回顾
- **名家讲堂**：在线讲座直播预告、倒计时、微信分享
- **名家风采**：医学专家列表、专家详情
- **往期视频**：录播视频在线观看
- **后台管理**：用户管理、专家管理、直播管理、录播管理

## 技术栈

- PHP 8.x
- Laravel 8
- MySQL 8.0
- Bootstrap 5
- jQuery

## 安装步骤

1. 克隆项目

```bash
git clone [项目地址]
cd duoruiji
```

2. 安装依赖

```bash
composer install
npm install
npm run dev
```

3. 配置环境

```bash
cp .env.example .env
php artisan key:generate
```

4. 配置数据库

编辑 `.env` 文件，配置数据库连接信息

5. 运行迁移

```bash
php artisan migrate
```

6. 填充测试数据

```bash
php artisan db:seed
```

7. 创建存储链接

```bash
php artisan storage:link
```

8. 启动服务

```bash
php artisan serve
```

## 默认管理员账号

- 邮箱：admin@duoruiji.com
- 密码：password

## 项目结构

```
duoruiji/
├── app/                # 应用代码
├── database/           # 数据库迁移和填充
├── public/             # 公共资源
├── resources/          # 视图和资源
├── routes/             # 路由定义
└── storage/            # 存储
```

## 开发团队

- 项目组长：[姓名]
- 开发成员：[姓名]

## 工期

2周（10个工作日）

## 验收标准

- [ ] 项目功能完成度
- [ ] 项目文档完成度
- [ ] 项目测试和部署情况
- [ ] 小组互评分数
- [ ] 组内工作量分配详情
