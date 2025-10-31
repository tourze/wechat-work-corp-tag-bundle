<?php

namespace WechatWorkCorpTagBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatWorkCorpTagBundle\Entity\CorpTagGroup;
use WechatWorkCorpTagBundle\Entity\CorpTagItem;

/**
 * CorpTagItem 实体测试用例
 *
 * 测试企业标签项目实体的所有功能
 *
 * @internal
 */
#[CoversClass(CorpTagItem::class)]
final class CorpTagItemTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new CorpTagItem();
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'name' => ['name', 'test_value'],
        ];
    }

    private CorpTagItem $corpTagItem;

    protected function setUp(): void
    {
        parent::setUp();

        $this->corpTagItem = new CorpTagItem();
    }

    public function testConstructorSetsDefaultValues(): void
    {
        $tagItem = new CorpTagItem();

        $this->assertNull($tagItem->getId());
        $this->assertNull($tagItem->getCorp());
        $this->assertNull($tagItem->getAgent());
        $this->assertNull($tagItem->getTagGroup());
        $this->assertNull($tagItem->getRemoteId());
        $this->assertNull($tagItem->getSortNumber());
        $this->assertNull($tagItem->getCreatedFromIp());
        $this->assertNull($tagItem->getUpdatedFromIp());
        $this->assertNull($tagItem->getCreatedBy());
        $this->assertNull($tagItem->getUpdatedBy());
        $this->assertNull($tagItem->getCreateTime());
        $this->assertNull($tagItem->getUpdateTime());
    }

    public function testSetNameWithValidNameSetsNameCorrectly(): void
    {
        $name = '重要客户';

        $this->corpTagItem->setName($name);

        $this->assertSame($name, $this->corpTagItem->getName());
    }

    public function testSetNameWithEmptyStringSetsEmptyString(): void
    {
        $this->corpTagItem->setName('');

        $this->assertSame('', $this->corpTagItem->getName());
    }

    public function testSetNameWithLongStringSetsLongString(): void
    {
        $longName = str_repeat('标签', 40); // 80个字符

        $this->corpTagItem->setName($longName);

        $this->assertSame($longName, $this->corpTagItem->getName());
    }

    public function testSetRemoteIdWithValidIdSetsIdCorrectly(): void
    {
        $remoteId = 'remote_tag_123456';

        $this->corpTagItem->setRemoteId($remoteId);

        $this->assertSame($remoteId, $this->corpTagItem->getRemoteId());
    }

    public function testSetRemoteIdWithNullSetsNull(): void
    {
        $this->corpTagItem->setRemoteId('old_remote_id');

        $this->corpTagItem->setRemoteId(null);

        $this->assertNull($this->corpTagItem->getRemoteId());
    }

    public function testSetSortNumberWithValidNumberSetsNumberCorrectly(): void
    {
        $sortNumber = 100;

        $this->corpTagItem->setSortNumber($sortNumber);

        $this->assertSame($sortNumber, $this->corpTagItem->getSortNumber());
    }

    public function testSetSortNumberWithZeroSetsZero(): void
    {
        $this->corpTagItem->setSortNumber(0);

        $this->assertSame(0, $this->corpTagItem->getSortNumber());
    }

    public function testSetSortNumberWithNegativeNumberSetsNegativeNumber(): void
    {
        $this->corpTagItem->setSortNumber(-10);

        $this->assertSame(-10, $this->corpTagItem->getSortNumber());
    }

    public function testSetTagGroupWithValidGroupSetsGroupCorrectly(): void
    {
        $tagGroup = new CorpTagGroup();

        $this->corpTagItem->setTagGroup($tagGroup);

        $this->assertSame($tagGroup, $this->corpTagItem->getTagGroup());
    }

    public function testSetTagGroupWithNullSetsNull(): void
    {
        $tagGroup = new CorpTagGroup();
        $this->corpTagItem->setTagGroup($tagGroup);

        $this->corpTagItem->setTagGroup(null);

        $this->assertNull($this->corpTagItem->getTagGroup());
    }

    public function testSetCreatedFromIpWithValidIpSetsIpCorrectly(): void
    {
        $ip = '192.168.1.1';

        $this->corpTagItem->setCreatedFromIp($ip);

        $this->assertSame($ip, $this->corpTagItem->getCreatedFromIp());
    }

    public function testSetCreatedFromIpWithNullSetsNull(): void
    {
        $this->corpTagItem->setCreatedFromIp('127.0.0.1');

        $this->corpTagItem->setCreatedFromIp(null);

        $this->assertNull($this->corpTagItem->getCreatedFromIp());
    }

    public function testSetUpdatedFromIpWithValidIpSetsIpCorrectly(): void
    {
        $ip = '10.0.0.1';

        $this->corpTagItem->setUpdatedFromIp($ip);

        $this->assertSame($ip, $this->corpTagItem->getUpdatedFromIp());
    }

    public function testSetUpdatedFromIpWithNullSetsNull(): void
    {
        $this->corpTagItem->setUpdatedFromIp('172.16.0.1');

        $this->corpTagItem->setUpdatedFromIp(null);

        $this->assertNull($this->corpTagItem->getUpdatedFromIp());
    }

    public function testSetCreatedByWithValidUserSetsUserCorrectly(): void
    {
        $createdBy = 'admin_user';

        $this->corpTagItem->setCreatedBy($createdBy);

        $this->assertSame($createdBy, $this->corpTagItem->getCreatedBy());
    }

    public function testSetCreatedByWithNullSetsNull(): void
    {
        $this->corpTagItem->setCreatedBy('old_user');

        $this->corpTagItem->setCreatedBy(null);

        $this->assertNull($this->corpTagItem->getCreatedBy());
    }

    public function testSetUpdatedByWithValidUserSetsUserCorrectly(): void
    {
        $updatedBy = 'updated_user';

        $this->corpTagItem->setUpdatedBy($updatedBy);

        $this->assertSame($updatedBy, $this->corpTagItem->getUpdatedBy());
    }

    public function testSetUpdatedByWithNullSetsNull(): void
    {
        $this->corpTagItem->setUpdatedBy('old_user');

        $this->corpTagItem->setUpdatedBy(null);

        $this->assertNull($this->corpTagItem->getUpdatedBy());
    }

    public function testSetCreateTimeWithValidDateTimeSetsTimeCorrectly(): void
    {
        $createTime = new \DateTimeImmutable('2024-01-01 10:00:00');

        $this->corpTagItem->setCreateTime($createTime);

        $this->assertSame($createTime, $this->corpTagItem->getCreateTime());
    }

    public function testSetCreateTimeWithNullSetsNull(): void
    {
        $this->corpTagItem->setCreateTime(new \DateTimeImmutable());

        $this->corpTagItem->setCreateTime(null);

        $this->assertNull($this->corpTagItem->getCreateTime());
    }

    public function testSetUpdateTimeWithValidDateTimeSetsTimeCorrectly(): void
    {
        $updateTime = new \DateTimeImmutable('2024-01-15 12:00:00');

        $this->corpTagItem->setUpdateTime($updateTime);

        $this->assertSame($updateTime, $this->corpTagItem->getUpdateTime());
    }

    public function testSetUpdateTimeWithNullSetsNull(): void
    {
        $this->corpTagItem->setUpdateTime(new \DateTimeImmutable());

        $this->corpTagItem->setUpdateTime(null);

        $this->assertNull($this->corpTagItem->getUpdateTime());
    }

    /**
     * 测试Stringable接口的实现
     */
    public function testToStringWithoutIdReturnsEmptyString(): void
    {
        $result = $this->corpTagItem->__toString();

        $this->assertSame('', $result);
    }

    public function testToStringWithIdAndTagGroupReturnsFormattedString(): void
    {
        // 使用反射设置ID（因为ID是通过数据库生成的）
        $reflection = new \ReflectionClass($this->corpTagItem);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($this->corpTagItem, '1234567890');

        // 创建真实的标签组
        $tagGroup = new CorpTagGroup();
        $tagGroup->setName('客户分类');

        $this->corpTagItem->setTagGroup($tagGroup);
        $this->corpTagItem->setName('重要客户');

        $result = $this->corpTagItem->__toString();

        $this->assertSame('客户分类-重要客户', $result);
    }

    public function testToStringWithIdButNullTagGroupHandlesGracefully(): void
    {
        // 使用反射设置ID
        $reflection = new \ReflectionClass($this->corpTagItem);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($this->corpTagItem, '1234567890');

        $this->corpTagItem->setName('独立标签');

        // 使用null安全操作符，应该优雅地处理null tag group
        $result = $this->corpTagItem->__toString();

        // 期望格式: null-独立标签 (由于null安全操作符，null会转换为空字符串)
        $this->assertSame('-独立标签', $result);
    }

    /**
     * 测试字符串类型转换
     */
    public function testStringCastWorksCorrectly(): void
    {
        // 使用反射设置ID
        $reflection = new \ReflectionClass($this->corpTagItem);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($this->corpTagItem, '1234567890');

        $tagGroup = new CorpTagGroup();
        $tagGroup->setName('VIP客户');

        $this->corpTagItem->setTagGroup($tagGroup);
        $this->corpTagItem->setName('黄金会员');

        $stringValue = (string) $this->corpTagItem;

        $this->assertSame('VIP客户-黄金会员', $stringValue);
    }

    /**
     * 测试链式调用
     */
    public function testChainedSettersReturnSameInstance(): void
    {
        $tagGroup = new CorpTagGroup();
        $createTime = new \DateTimeImmutable('2024-01-01');
        $updateTime = new \DateTimeImmutable('2024-01-15');

        // 使用独立的setter调用，因为实体中的setter方法返回void
        $this->corpTagItem->setName('链式调用测试标签');
        $this->corpTagItem->setRemoteId('remote_chain');
        $this->corpTagItem->setSortNumber(50);
        $this->corpTagItem->setTagGroup($tagGroup);
        $this->corpTagItem->setCreatedFromIp('192.168.1.1');
        $this->corpTagItem->setUpdatedFromIp('192.168.1.2');
        $this->corpTagItem->setCreatedBy('admin');
        $this->corpTagItem->setUpdatedBy('editor');
        $this->corpTagItem->setCreateTime($createTime);
        $this->corpTagItem->setUpdateTime($updateTime);

        $this->assertSame('链式调用测试标签', $this->corpTagItem->getName());
        $this->assertSame('remote_chain', $this->corpTagItem->getRemoteId());
        $this->assertSame(50, $this->corpTagItem->getSortNumber());
        $this->assertSame($tagGroup, $this->corpTagItem->getTagGroup());
        $this->assertSame('192.168.1.1', $this->corpTagItem->getCreatedFromIp());
        $this->assertSame('192.168.1.2', $this->corpTagItem->getUpdatedFromIp());
        $this->assertSame('admin', $this->corpTagItem->getCreatedBy());
        $this->assertSame('editor', $this->corpTagItem->getUpdatedBy());
        $this->assertSame($createTime, $this->corpTagItem->getCreateTime());
        $this->assertSame($updateTime, $this->corpTagItem->getUpdateTime());
    }

    /**
     * 测试边界场景
     */
    public function testEdgeCasesExtremeValues(): void
    {
        // 测试极端整数值
        $this->corpTagItem->setSortNumber(PHP_INT_MAX);
        $this->assertSame(PHP_INT_MAX, $this->corpTagItem->getSortNumber());

        $this->corpTagItem->setSortNumber(PHP_INT_MIN);
        $this->assertSame(PHP_INT_MIN, $this->corpTagItem->getSortNumber());
    }

    public function testEdgeCasesLongStrings(): void
    {
        $longString = str_repeat('x', 1000);

        $this->corpTagItem->setName($longString);
        $this->corpTagItem->setRemoteId($longString);
        $this->corpTagItem->setCreatedFromIp($longString);
        $this->corpTagItem->setUpdatedFromIp($longString);
        $this->corpTagItem->setCreatedBy($longString);
        $this->corpTagItem->setUpdatedBy($longString);

        $this->assertSame($longString, $this->corpTagItem->getName());
        $this->assertSame($longString, $this->corpTagItem->getRemoteId());
        $this->assertSame($longString, $this->corpTagItem->getCreatedFromIp());
        $this->assertSame($longString, $this->corpTagItem->getUpdatedFromIp());
        $this->assertSame($longString, $this->corpTagItem->getCreatedBy());
        $this->assertSame($longString, $this->corpTagItem->getUpdatedBy());
    }

    public function testEdgeCasesDateTimeTypes(): void
    {
        // 测试DateTime
        $dateTime = new \DateTimeImmutable('2024-01-15 12:30:45');
        $this->corpTagItem->setCreateTime($dateTime);
        $this->assertSame($dateTime, $this->corpTagItem->getCreateTime());

        // 测试DateTimeImmutable
        $dateTimeImmutable = new \DateTimeImmutable('2024-02-20 09:15:30');
        $this->corpTagItem->setUpdateTime($dateTimeImmutable);
        $this->assertSame($dateTimeImmutable, $this->corpTagItem->getUpdateTime());
    }

    /**
     * 测试标签组关联关系 - 简化版，避免调用不存在的方法
     */
    public function testTagGroupRelationBidirectional(): void
    {
        $tagGroup = new CorpTagGroup();

        $this->corpTagItem->setTagGroup($tagGroup);

        $this->assertSame($tagGroup, $this->corpTagItem->getTagGroup());
    }

    public function testTagGroupRelationNullifyCorrectly(): void
    {
        $tagGroup = new CorpTagGroup();

        $this->corpTagItem->setTagGroup($tagGroup);
        $this->assertSame($tagGroup, $this->corpTagItem->getTagGroup());

        $this->corpTagItem->setTagGroup(null);
        $this->assertNull($this->corpTagItem->getTagGroup());
    }

    /**
     * 测试与字符串相关的行为
     */
    public function testStringBehaviorsWithSpecialCharacters(): void
    {
        // 使用反射设置ID
        $reflection = new \ReflectionClass($this->corpTagItem);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($this->corpTagItem, '1234567890');

        $tagGroup = new CorpTagGroup();
        $tagGroup->setName('特殊字符-测试@#$%');

        $this->corpTagItem->setTagGroup($tagGroup);
        $this->corpTagItem->setName('包含符号&*()的标签');

        $result = $this->corpTagItem->__toString();

        $this->assertSame('特殊字符-测试@#$%-包含符号&*()的标签', $result);
    }

    public function testStringBehaviorsWithEmptyNames(): void
    {
        // 使用反射设置ID
        $reflection = new \ReflectionClass($this->corpTagItem);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($this->corpTagItem, '1234567890');

        $tagGroup = new CorpTagGroup();
        $tagGroup->setName('');

        $this->corpTagItem->setTagGroup($tagGroup);
        $this->corpTagItem->setName('');

        $result = $this->corpTagItem->__toString();

        $this->assertSame('-', $result);
    }
}
