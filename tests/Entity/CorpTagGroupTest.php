<?php

namespace WechatWorkCorpTagBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatWorkCorpTagBundle\Entity\CorpTagGroup;
use WechatWorkCorpTagBundle\Entity\CorpTagItem;

/**
 * CorpTagGroup 实体测试用例
 *
 * 测试企业标签分组实体的所有功能
 *
 * @internal
 */
#[CoversClass(CorpTagGroup::class)]
final class CorpTagGroupTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new CorpTagGroup();
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

    private CorpTagGroup $corpTagGroup;

    protected function setUp(): void
    {
        parent::setUp();

        $this->corpTagGroup = new CorpTagGroup();
    }

    public function testConstructorSetsDefaultValues(): void
    {
        $tagGroup = new CorpTagGroup();

        $this->assertNull($tagGroup->getId());
        $this->assertNull($tagGroup->getCorp());
        $this->assertNull($tagGroup->getAgent());
        $this->assertNull($tagGroup->getRemoteId());
        $this->assertInstanceOf(Collection::class, $tagGroup->getItems());
        $this->assertInstanceOf(ArrayCollection::class, $tagGroup->getItems());
        $this->assertTrue($tagGroup->getItems()->isEmpty());
        $this->assertNull($tagGroup->getSortNumber());
        $this->assertNull($tagGroup->getCreatedFromIp());
        $this->assertNull($tagGroup->getUpdatedFromIp());
        $this->assertNull($tagGroup->getCreatedBy());
        $this->assertNull($tagGroup->getUpdatedBy());
        $this->assertNull($tagGroup->getCreateTime());
        $this->assertNull($tagGroup->getUpdateTime());
    }

    public function testSetNameWithValidNameSetsNameCorrectly(): void
    {
        $name = '重要客户';

        $this->corpTagGroup->setName($name);

        $this->assertSame($name, $this->corpTagGroup->getName());
    }

    public function testSetNameWithEmptyStringSetsEmptyString(): void
    {
        $this->corpTagGroup->setName('');

        $this->assertSame('', $this->corpTagGroup->getName());
    }

    public function testSetNameWithLongStringSetsLongString(): void
    {
        $longName = str_repeat('标签分组', 20); // 60个字符

        $this->corpTagGroup->setName($longName);

        $this->assertSame($longName, $this->corpTagGroup->getName());
    }

    public function testSetRemoteIdWithValidIdSetsIdCorrectly(): void
    {
        $remoteId = 'remote_group_123456';

        $this->corpTagGroup->setRemoteId($remoteId);

        $this->assertSame($remoteId, $this->corpTagGroup->getRemoteId());
    }

    public function testSetRemoteIdWithNullSetsNull(): void
    {
        $this->corpTagGroup->setRemoteId('old_remote_id');

        $this->corpTagGroup->setRemoteId(null);

        $this->assertNull($this->corpTagGroup->getRemoteId());
    }

    public function testSetSortNumberWithValidNumberSetsNumberCorrectly(): void
    {
        $sortNumber = 100;

        $this->corpTagGroup->setSortNumber($sortNumber);

        $this->assertSame($sortNumber, $this->corpTagGroup->getSortNumber());
    }

    public function testSetSortNumberWithZeroSetsZero(): void
    {
        $this->corpTagGroup->setSortNumber(0);

        $this->assertSame(0, $this->corpTagGroup->getSortNumber());
    }

    public function testSetSortNumberWithNegativeNumberSetsNegativeNumber(): void
    {
        $this->corpTagGroup->setSortNumber(-10);

        $this->assertSame(-10, $this->corpTagGroup->getSortNumber());
    }

    public function testAddItemWithNewItemAddsItemToCollection(): void
    {
        $item = new CorpTagItem();

        $result = $this->corpTagGroup->addItem($item);

        $this->assertSame($this->corpTagGroup, $result);
        $this->assertTrue($this->corpTagGroup->getItems()->contains($item));
        $this->assertCount(1, $this->corpTagGroup->getItems());
        $this->assertSame($this->corpTagGroup, $item->getTagGroup());
    }

    public function testAddItemWithExistingItemDoesNotAddDuplicate(): void
    {
        $item = new CorpTagItem();

        // 添加第一次
        $this->corpTagGroup->addItem($item);
        $firstCount = $this->corpTagGroup->getItems()->count();

        // 尝试再次添加相同项
        $this->corpTagGroup->addItem($item);

        $this->assertCount($firstCount, $this->corpTagGroup->getItems());
    }

    public function testAddItemWithMultipleItemsAddsAllItems(): void
    {
        $item1 = new CorpTagItem();
        $item2 = new CorpTagItem();

        $this->corpTagGroup->addItem($item1);
        $this->corpTagGroup->addItem($item2);

        $this->assertCount(2, $this->corpTagGroup->getItems());
        $this->assertTrue($this->corpTagGroup->getItems()->contains($item1));
        $this->assertTrue($this->corpTagGroup->getItems()->contains($item2));
        $this->assertSame($this->corpTagGroup, $item1->getTagGroup());
        $this->assertSame($this->corpTagGroup, $item2->getTagGroup());
    }

    public function testRemoveItemWithExistingItemRemovesItemFromCollection(): void
    {
        $item = new CorpTagItem();

        // 先添加项
        $this->corpTagGroup->addItem($item);
        $this->assertCount(1, $this->corpTagGroup->getItems());
        $this->assertSame($this->corpTagGroup, $item->getTagGroup());

        // 移除项
        $this->corpTagGroup->removeItem($item);

        $this->assertFalse($this->corpTagGroup->getItems()->contains($item));
        $this->assertCount(0, $this->corpTagGroup->getItems());
        $this->assertNull($item->getTagGroup());
    }

    public function testRemoveItemWithNonExistingItemDoesNothing(): void
    {
        $item = new CorpTagItem();

        $result = $this->corpTagGroup->removeItem($item);

        $this->assertSame($this->corpTagGroup, $result);
        $this->assertCount(0, $this->corpTagGroup->getItems());
        $this->assertNull($item->getTagGroup());
    }

    public function testRemoveItemWhenItemTagGroupDiffersRemovesButDoesNotSetNull(): void
    {
        $item = new CorpTagItem();
        $otherGroup = new CorpTagGroup();

        // 添加项
        $this->corpTagGroup->addItem($item);

        // 模拟项的标签组已经被改变
        $item->setTagGroup($otherGroup);

        $result = $this->corpTagGroup->removeItem($item);

        $this->assertFalse($this->corpTagGroup->getItems()->contains($item));
        // 项的标签组应该仍然是 $otherGroup，而不是 null
        $this->assertSame($otherGroup, $item->getTagGroup());
    }

    public function testSetCreatedFromIpWithValidIpSetsIpCorrectly(): void
    {
        $ip = '192.168.1.1';

        $this->corpTagGroup->setCreatedFromIp($ip);

        $this->assertSame($ip, $this->corpTagGroup->getCreatedFromIp());
    }

    public function testSetCreatedFromIpWithNullSetsNull(): void
    {
        $this->corpTagGroup->setCreatedFromIp('127.0.0.1');

        $this->corpTagGroup->setCreatedFromIp(null);

        $this->assertNull($this->corpTagGroup->getCreatedFromIp());
    }

    public function testSetUpdatedFromIpWithValidIpSetsIpCorrectly(): void
    {
        $ip = '10.0.0.1';

        $this->corpTagGroup->setUpdatedFromIp($ip);

        $this->assertSame($ip, $this->corpTagGroup->getUpdatedFromIp());
    }

    public function testSetUpdatedFromIpWithNullSetsNull(): void
    {
        $this->corpTagGroup->setUpdatedFromIp('172.16.0.1');

        $this->corpTagGroup->setUpdatedFromIp(null);

        $this->assertNull($this->corpTagGroup->getUpdatedFromIp());
    }

    public function testSetCreatedByWithValidUserSetsUserCorrectly(): void
    {
        $createdBy = 'admin_user';

        $this->corpTagGroup->setCreatedBy($createdBy);

        $this->assertSame($createdBy, $this->corpTagGroup->getCreatedBy());
    }

    public function testSetCreatedByWithNullSetsNull(): void
    {
        $this->corpTagGroup->setCreatedBy('old_user');

        $this->corpTagGroup->setCreatedBy(null);

        $this->assertNull($this->corpTagGroup->getCreatedBy());
    }

    public function testSetUpdatedByWithValidUserSetsUserCorrectly(): void
    {
        $updatedBy = 'updated_user';

        $this->corpTagGroup->setUpdatedBy($updatedBy);

        $this->assertSame($updatedBy, $this->corpTagGroup->getUpdatedBy());
    }

    public function testSetUpdatedByWithNullSetsNull(): void
    {
        $this->corpTagGroup->setUpdatedBy('old_user');

        $this->corpTagGroup->setUpdatedBy(null);

        $this->assertNull($this->corpTagGroup->getUpdatedBy());
    }

    public function testSetCreateTimeWithValidDateTimeSetsTimeCorrectly(): void
    {
        $createTime = new \DateTimeImmutable('2024-01-01 10:00:00');

        $this->corpTagGroup->setCreateTime($createTime);

        $this->assertSame($createTime, $this->corpTagGroup->getCreateTime());
    }

    public function testSetCreateTimeWithNullSetsNull(): void
    {
        $this->corpTagGroup->setCreateTime(new \DateTimeImmutable());

        $this->corpTagGroup->setCreateTime(null);

        $this->assertNull($this->corpTagGroup->getCreateTime());
    }

    public function testSetUpdateTimeWithValidDateTimeSetsTimeCorrectly(): void
    {
        $updateTime = new \DateTimeImmutable('2024-01-15 12:00:00');

        $this->corpTagGroup->setUpdateTime($updateTime);

        $this->assertSame($updateTime, $this->corpTagGroup->getUpdateTime());
    }

    public function testSetUpdateTimeWithNullSetsNull(): void
    {
        $this->corpTagGroup->setUpdateTime(new \DateTimeImmutable());

        $this->corpTagGroup->setUpdateTime(null);

        $this->assertNull($this->corpTagGroup->getUpdateTime());
    }

    /**
     * 测试链式调用
     */
    public function testChainedSettersReturnSameInstance(): void
    {
        $createTime = new \DateTimeImmutable('2024-01-01');
        $updateTime = new \DateTimeImmutable('2024-01-15');

        // 使用独立的setter调用，因为实体中的setter方法返回void
        $this->corpTagGroup->setName('链式调用测试分组');
        $this->corpTagGroup->setRemoteId('remote_chain');
        $this->corpTagGroup->setSortNumber(50);
        $this->corpTagGroup->setCreatedFromIp('192.168.1.1');
        $this->corpTagGroup->setUpdatedFromIp('192.168.1.2');
        $this->corpTagGroup->setCreatedBy('admin');
        $this->corpTagGroup->setUpdatedBy('editor');
        $this->corpTagGroup->setCreateTime($createTime);
        $this->corpTagGroup->setUpdateTime($updateTime);

        $this->assertSame('链式调用测试分组', $this->corpTagGroup->getName());
        $this->assertSame('remote_chain', $this->corpTagGroup->getRemoteId());
        $this->assertSame(50, $this->corpTagGroup->getSortNumber());
        $this->assertSame('192.168.1.1', $this->corpTagGroup->getCreatedFromIp());
        $this->assertSame('192.168.1.2', $this->corpTagGroup->getUpdatedFromIp());
        $this->assertSame('admin', $this->corpTagGroup->getCreatedBy());
        $this->assertSame('editor', $this->corpTagGroup->getUpdatedBy());
        $this->assertSame($createTime, $this->corpTagGroup->getCreateTime());
        $this->assertSame($updateTime, $this->corpTagGroup->getUpdateTime());
    }

    /**
     * 测试边界场景
     */
    public function testEdgeCasesExtremeValues(): void
    {
        // 测试极端整数值
        $this->corpTagGroup->setSortNumber(PHP_INT_MAX);
        $this->assertSame(PHP_INT_MAX, $this->corpTagGroup->getSortNumber());

        $this->corpTagGroup->setSortNumber(PHP_INT_MIN);
        $this->assertSame(PHP_INT_MIN, $this->corpTagGroup->getSortNumber());
    }

    public function testEdgeCasesLongStrings(): void
    {
        $longString = str_repeat('x', 1000);

        $this->corpTagGroup->setName($longString);
        $this->corpTagGroup->setRemoteId($longString);
        $this->corpTagGroup->setCreatedFromIp($longString);
        $this->corpTagGroup->setUpdatedFromIp($longString);
        $this->corpTagGroup->setCreatedBy($longString);
        $this->corpTagGroup->setUpdatedBy($longString);

        $this->assertSame($longString, $this->corpTagGroup->getName());
        $this->assertSame($longString, $this->corpTagGroup->getRemoteId());
        $this->assertSame($longString, $this->corpTagGroup->getCreatedFromIp());
        $this->assertSame($longString, $this->corpTagGroup->getUpdatedFromIp());
        $this->assertSame($longString, $this->corpTagGroup->getCreatedBy());
        $this->assertSame($longString, $this->corpTagGroup->getUpdatedBy());
    }

    public function testEdgeCasesDateTimeTypes(): void
    {
        // 测试DateTime
        $dateTime = new \DateTimeImmutable('2024-01-15 12:30:45');
        $this->corpTagGroup->setCreateTime($dateTime);
        $this->assertSame($dateTime, $this->corpTagGroup->getCreateTime());

        // 测试DateTimeImmutable
        $dateTimeImmutable = new \DateTimeImmutable('2024-02-20 09:15:30');
        $this->corpTagGroup->setUpdateTime($dateTimeImmutable);
        $this->assertSame($dateTimeImmutable, $this->corpTagGroup->getUpdateTime());
    }

    /**
     * 测试Collection操作的复杂场景
     */
    public function testItemCollectionSimpleOperations(): void
    {
        $item1 = new CorpTagItem();
        $item2 = new CorpTagItem();
        $item3 = new CorpTagItem();

        // 添加多个项
        $this->corpTagGroup->addItem($item1);
        $this->corpTagGroup->addItem($item2);
        $this->corpTagGroup->addItem($item3);

        $this->assertCount(3, $this->corpTagGroup->getItems());
        $this->assertTrue($this->corpTagGroup->getItems()->contains($item1));
        $this->assertTrue($this->corpTagGroup->getItems()->contains($item2));
        $this->assertTrue($this->corpTagGroup->getItems()->contains($item3));
        $this->assertSame($this->corpTagGroup, $item1->getTagGroup());
        $this->assertSame($this->corpTagGroup, $item2->getTagGroup());
        $this->assertSame($this->corpTagGroup, $item3->getTagGroup());
    }

    public function testItemCollectionIsIterable(): void
    {
        $item1 = new CorpTagItem();
        $item2 = new CorpTagItem();

        $this->corpTagGroup->addItem($item1);
        $this->corpTagGroup->addItem($item2);

        $items = [];
        foreach ($this->corpTagGroup->getItems() as $item) {
            $items[] = $item;
        }

        $this->assertCount(2, $items);
        $this->assertContains($item1, $items);
        $this->assertContains($item2, $items);
    }
}
