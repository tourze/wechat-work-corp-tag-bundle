# 企业微信标签管理包

[![Latest Version](https://img.shields.io/packagist/v/tourze/wechat-work-corp-tag-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-work-corp-tag-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/wechat-work-corp-tag-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-work-corp-tag-bundle)
[![PHP Version Require](https://img.shields.io/badge/php-%3E%3D8.1-787CB5)](https://php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

[English](README.md) | [中文](README.zh-CN.md)

一个用于管理企业微信公司标签的 Symfony 包，提供全面的标签组和标签项管理功能。

## 特性

- **标签组管理**：创建、更新和管理企业标签组
- **标签项管理**：处理组内的单个标签
- **Doctrine 集成**：完整的 ORM 支持和仓储模式
- **企业微信 API**：与企业微信公司 API 无缝集成
- **Symfony 包**：原生 Symfony 框架集成

## 安装

```bash
composer require tourze/wechat-work-corp-tag-bundle
```

## 依赖

此包需要：

- PHP 8.1 或更高版本
- Symfony 6.4 或更高版本
- Doctrine ORM 3.0 或更高版本
- WeChat Work Bundle (`tourze/wechat-work-bundle`)
- WeChat Work Contracts (`tourze/wechat-work-contracts`)

## 快速开始

### 1. 启用包

在 `config/bundles.php` 中添加包：

```php
return [
    // ...
    WechatWorkCorpTagBundle\WechatWorkCorpTagBundle::class => ['all' => true],
];
```

### 2. 配置数据库

运行迁移以创建必要的表：

```bash
php bin/console doctrine:migrations:migrate
```

### 3. 基本使用

```php
use WechatWorkCorpTagBundle\Entity\CorpTagGroup;
use WechatWorkCorpTagBundle\Entity\CorpTagItem;

// 创建标签组
$tagGroup = new CorpTagGroup();
$tagGroup->setName('部门标签');
$tagGroup->setCorp($corp);
$tagGroup->setAgent($agent);

// 创建标签项
$tagItem = new CorpTagItem();
$tagItem->setName('工程部');
$tagItem->setTagGroup($tagGroup);
```

### 4. API 请求

该包提供了用于企业微信 API 交互的请求类：

```php
use WechatWorkCorpTagBundle\Request\AddCorpTagRequest;
use WechatWorkCorpTagBundle\Request\GetCorpTagListRequest;

// 添加新的企业标签
$addRequest = new AddCorpTagRequest();
$addRequest->setTagName('新标签');

// 获取标签列表
$listRequest = new GetCorpTagListRequest();
$tags = $listRequest->getTags();
```

## 配置

该包通过依赖注入自动配置服务。无需额外配置。

## Advanced Usage

### 仓储使用

包提供了用于高级查询的仓储类：

```php
use WechatWorkCorpTagBundle\Repository\CorpTagGroupRepository;
use WechatWorkCorpTagBundle\Repository\CorpTagItemRepository;

// 在服务中注入仓储
public function __construct(
    private CorpTagGroupRepository $tagGroupRepository,
    private CorpTagItemRepository $tagItemRepository
) {}

// 按组查找标签
$tagItems = $this->tagItemRepository->findBy(['tagGroup' => $tagGroup]);

// 自定义查询
$popularTags = $this->tagItemRepository->createQueryBuilder('t')
    ->where('t.sortNumber > :minSort')
    ->setParameter('minSort', 100)
    ->getQuery()
    ->getResult();
```

### 与企业微信同步

包提供与企业微信 API 的自动同步功能：

```php
// 将标签项同步到远程企业微信
$tagItem->syncToRemote(
    $tagGroupRepository,
    $tagItemRepository,
    $workService,
    $logger,
    $entityManager
);
```

### 事件处理

您可以监听 Doctrine 事件以实现自定义业务逻辑：

```php
use Doctrine\ORM\Events;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;

#[AsDoctrineListener(event: Events::prePersist)]
class TagEventListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        
        if ($entity instanceof CorpTagItem) {
            // 保存标签前的自定义逻辑
        }
    }
}
```

## API 文档

该包实现了企业微信企业标签 API：
- [企业标签管理](https://developer.work.weixin.qq.com/document/path/92117)

## 测试

运行测试：

```bash
./vendor/bin/phpunit packages/wechat-work-corp-tag-bundle/tests
```

## 更新日志

### 版本 0.1.x
- 初始版本发布
- 基本的标签组和标签项管理
- 企业微信 API 集成
- Doctrine ORM 支持

详细更改请参阅 [CHANGELOG.md](CHANGELOG.md) 文件。

## 贡献

欢迎贡献！请遵循项目的编码规范，并为任何新功能添加测试。

如何贡献：
1. Fork 仓库
2. 创建功能分支
3. 为您的更改添加测试
4. 确保所有测试通过
5. 提交拉取请求

## 许可证

该包基于 MIT 许可证发布。详情请参阅 [LICENSE](LICENSE) 文件。
