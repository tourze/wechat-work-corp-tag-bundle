<?php

declare(strict_types=1);

namespace WechatWorkCorpTagBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use WechatWorkCorpTagBundle\WechatWorkCorpTagBundle;

/**
 * @internal
 */
#[CoversClass(WechatWorkCorpTagBundle::class)]
#[RunTestsInSeparateProcesses]
final class WechatWorkCorpTagBundleTest extends AbstractBundleTestCase
{
}
