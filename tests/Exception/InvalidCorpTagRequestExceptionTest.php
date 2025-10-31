<?php

declare(strict_types=1);

namespace WechatWorkCorpTagBundle\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use WechatWorkCorpTagBundle\Exception\InvalidCorpTagRequestException;

/**
 * InvalidCorpTagRequestException 测试用例
 *
 * @internal
 */
#[CoversClass(InvalidCorpTagRequestException::class)]
final class InvalidCorpTagRequestExceptionTest extends AbstractExceptionTestCase
{
    public function testIsInstanceOfInvalidArgumentException(): void
    {
        $exception = new InvalidCorpTagRequestException('测试消息');

        $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
    }

    public function testCanBeConstructedWithMessage(): void
    {
        $message = '标签参数无效';
        $exception = new InvalidCorpTagRequestException($message);

        $this->assertSame($message, $exception->getMessage());
    }

    public function testCanBeConstructedWithMessageAndCode(): void
    {
        $message = '标签参数无效';
        $code = 400;
        $exception = new InvalidCorpTagRequestException($message, $code);

        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($code, $exception->getCode());
    }

    public function testCanBeThrownAndCaught(): void
    {
        $this->expectException(InvalidCorpTagRequestException::class);
        $this->expectExceptionMessage('测试异常抛出');

        throw new InvalidCorpTagRequestException('测试异常抛出');
    }
}
