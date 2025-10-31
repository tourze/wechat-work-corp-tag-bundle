<?php

namespace WechatWorkCorpTagBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;
use WechatWorkCorpTagBundle\DependencyInjection\WechatWorkCorpTagExtension;

/**
 * WechatWorkCorpTagExtension 测试用例
 *
 * @internal
 */
#[CoversClass(WechatWorkCorpTagExtension::class)]
final class WechatWorkCorpTagExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    private WechatWorkCorpTagExtension $extension;

    private ContainerBuilder $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extension = new WechatWorkCorpTagExtension();
        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.environment', 'test');
    }

    public function testLoadLoadsServicesCorrectly(): void
    {
        $configs = [];

        $this->extension->load($configs, $this->container);

        // 验证基础服务配置已加载 - 通过检查容器中的服务定义
        $definitions = $this->container->getDefinitions();
        $this->assertNotEmpty($definitions, 'Extension should load service definitions');

        // 验证测试环境服务配置已加载
        $this->assertArrayHasKey('WechatWorkCorpTagBundle\DataFixtures\CorpTagGroupFixtures', $definitions);
    }

    public function testLoadWithEmptyConfig(): void
    {
        $this->extension->load([], $this->container);

        // 验证即使配置为空也能正常加载服务定义
        $definitions = $this->container->getDefinitions();
        $this->assertNotEmpty($definitions, 'Extension should load service definitions even with empty config');
    }

    public function testLoadMultipleInvocations(): void
    {
        // 测试多次调用 load 方法
        $this->extension->load([], $this->container);
        $definitionsCount1 = count($this->container->getDefinitions());

        $this->extension->load([], $this->container);
        $definitionsCount2 = count($this->container->getDefinitions());

        // 验证多次加载会增加服务定义数量
        $this->assertGreaterThanOrEqual($definitionsCount1, $definitionsCount2, 'Multiple loads should not reduce service definitions');
    }
}
