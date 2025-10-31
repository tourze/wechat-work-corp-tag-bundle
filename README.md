# Wechat Work Corp Tag Bundle

[![Latest Version](https://img.shields.io/packagist/v/tourze/wechat-work-corp-tag-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-work-corp-tag-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/wechat-work-corp-tag-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-work-corp-tag-bundle)
[![PHP Version Require](https://img.shields.io/badge/php-%3E%3D8.1-787CB5)](https://php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

[English](README.md) | [中文](README.zh-CN.md)

A Symfony bundle for managing WeChat Work corporate tags, providing comprehensive tag group and tag item management functionality.

## Features

- **Tag Group Management**: Create, update, and manage corporate tag groups
- **Tag Item Management**: Handle individual tags within groups
- **Doctrine Integration**: Full ORM support with repository patterns
- **WeChat Work API**: Seamless integration with WeChat Work corporate API
- **Symfony Bundle**: Native Symfony framework integration

## Installation

```bash
composer require tourze/wechat-work-corp-tag-bundle
```

## Dependencies

This bundle requires:

- PHP 8.1 or higher
- Symfony 6.4 or higher
- Doctrine ORM 3.0 or higher
- WeChat Work Bundle (`tourze/wechat-work-bundle`)
- WeChat Work Contracts (`tourze/wechat-work-contracts`)

## Quick Start

### 1. Enable the Bundle

Add the bundle to your `config/bundles.php`:

```php
return [
    // ...
    WechatWorkCorpTagBundle\WechatWorkCorpTagBundle::class => ['all' => true],
];
```

### 2. Configure Database

Run migrations to create the necessary tables:

```bash
php bin/console doctrine:migrations:migrate
```

### 3. Basic Usage

```php
use WechatWorkCorpTagBundle\Entity\CorpTagGroup;
use WechatWorkCorpTagBundle\Entity\CorpTagItem;

// Create a tag group
$tagGroup = new CorpTagGroup();
$tagGroup->setName('Department Tags');
$tagGroup->setCorp($corp);
$tagGroup->setAgent($agent);

// Create tag items
$tagItem = new CorpTagItem();
$tagItem->setName('Engineering');
$tagItem->setTagGroup($tagGroup);
```

### 4. API Requests

The bundle provides request classes for WeChat Work API interactions:

```php
use WechatWorkCorpTagBundle\Request\AddCorpTagRequest;
use WechatWorkCorpTagBundle\Request\GetCorpTagListRequest;

// Add new corporate tag
$addRequest = new AddCorpTagRequest();
$addRequest->setTagName('New Tag');

// Get tag list
$listRequest = new GetCorpTagListRequest();
$tags = $listRequest->getTags();
```

## Configuration

The bundle automatically configures services through dependency injection. No additional configuration is required.

## Advanced Usage

### Repository Usage

The bundle provides repository classes for advanced querying:

```php
use WechatWorkCorpTagBundle\Repository\CorpTagGroupRepository;
use WechatWorkCorpTagBundle\Repository\CorpTagItemRepository;

// Inject repositories in your services
public function __construct(
    private CorpTagGroupRepository $tagGroupRepository,
    private CorpTagItemRepository $tagItemRepository
) {}

// Find tags by group
$tagItems = $this->tagItemRepository->findBy(['tagGroup' => $tagGroup]);

// Custom queries
$popularTags = $this->tagItemRepository->createQueryBuilder('t')
    ->where('t.sortNumber > :minSort')
    ->setParameter('minSort', 100)
    ->getQuery()
    ->getResult();
```

### Synchronization with WeChat Work

The bundle provides automatic synchronization with WeChat Work API:

```php
// Sync a tag item to remote WeChat Work
$tagItem->syncToRemote(
    $tagGroupRepository,
    $tagItemRepository,
    $workService,
    $logger,
    $entityManager
);
```

### Event Handling

You can listen to Doctrine events for custom business logic:

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
            // Custom logic before saving tag
        }
    }
}
```

## API Documentation

This bundle implements the WeChat Work Corporate Tag API:
- [Corporate Tag Management](https://developer.work.weixin.qq.com/document/path/92117)

## Testing

To run tests:

```bash
./vendor/bin/phpunit packages/wechat-work-corp-tag-bundle/tests
```

## Changelog

### Version 0.1.x
- Initial release
- Basic tag group and tag item management
- WeChat Work API integration
- Doctrine ORM support

For detailed changes, see the [CHANGELOG.md](CHANGELOG.md) file.

## Contributing

Contributions are welcome! Please follow the project's coding standards and include tests for any new features.

To contribute:
1. Fork the repository
2. Create a feature branch
3. Add tests for your changes
4. Ensure all tests pass
5. Submit a pull request

## License

This bundle is released under the MIT License. See the [LICENSE](LICENSE) file for details.