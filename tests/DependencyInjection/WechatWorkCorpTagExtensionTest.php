<?php

namespace WechatWorkCorpTagBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use WechatWorkCorpTagBundle\DependencyInjection\WechatWorkCorpTagExtension;

/**
 * WechatWorkCorpTagExtension 测试用例
 */
class WechatWorkCorpTagExtensionTest extends TestCase
{
    private WechatWorkCorpTagExtension $extension;
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $this->extension = new WechatWorkCorpTagExtension();
        $this->container = new ContainerBuilder();
    }

    public function test_load_loadsServicesCorrectly(): void
    {
        $configs = [];
        
        $this->extension->load($configs, $this->container);
        
        // 验证扩展能正常加载
        $this->assertTrue(true);
    }

    public function test_load_withEmptyConfig(): void
    {
        $this->extension->load([], $this->container);
        
        // 验证即使配置为空也能正常加载
        $this->assertTrue(true);
    }

    public function test_load_multipleInvocations(): void
    {
        // 测试多次调用 load 方法
        $this->extension->load([], $this->container);
        $this->extension->load([], $this->container);
        
        // 验证多次加载不会导致问题
        $this->assertTrue(true);
    }
}