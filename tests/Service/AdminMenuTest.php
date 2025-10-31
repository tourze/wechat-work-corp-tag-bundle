<?php

declare(strict_types=1);

namespace WechatWorkCorpTagBundle\Tests\Service;

use Knp\Menu\MenuFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;
use WechatWorkCorpTagBundle\Service\AdminMenu;

/**
 * AdminMenu服务测试
 *
 * @internal
 */
#[CoversClass(AdminMenu::class)]
#[RunTestsInSeparateProcesses]
class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    protected function onSetUp(): void
    {
        // Setup for AdminMenu tests
    }

    public function testInvokeAddsMenuItems(): void
    {
        $container = self::getContainer();
        /** @var AdminMenu $adminMenu */
        $adminMenu = $container->get(AdminMenu::class);

        $factory = new MenuFactory();
        $rootItem = $factory->createItem('root');

        $adminMenu->__invoke($rootItem);

        // 验证菜单结构
        $wechatMenu = $rootItem->getChild('企微管理');
        self::assertNotNull($wechatMenu);

        $tagMenu = $wechatMenu->getChild('企业标签管理');
        self::assertNotNull($tagMenu);

        self::assertNotNull($tagMenu->getChild('标签分组'));
        self::assertNotNull($tagMenu->getChild('标签项目'));
    }
}
