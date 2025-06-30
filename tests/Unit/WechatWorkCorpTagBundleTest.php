<?php

namespace WechatWorkCorpTagBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WechatWorkCorpTagBundle\WechatWorkCorpTagBundle;

class WechatWorkCorpTagBundleTest extends TestCase
{
    public function testBundleCanBeInstantiated(): void
    {
        $bundle = new WechatWorkCorpTagBundle();
        self::assertInstanceOf(WechatWorkCorpTagBundle::class, $bundle);
    }
}