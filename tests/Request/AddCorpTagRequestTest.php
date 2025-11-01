<?php

declare(strict_types=1);

namespace WechatWorkCorpTagBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatWorkCorpTagBundle\Exception\InvalidCorpTagRequestException;
use WechatWorkCorpTagBundle\Request\AddCorpTagRequest;

/**
 * AddCorpTagRequest 测试用例
 *
 * @internal
 */
#[CoversClass(AddCorpTagRequest::class)]
final class AddCorpTagRequestTest extends RequestTestCase
{
    private AddCorpTagRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new AddCorpTagRequest();
    }

    public function testGetRequestPathReturnsCorrectPath(): void
    {
        $this->assertSame('/cgi-bin/externalcontact/add_corp_tag', $this->request->getRequestPath());
    }

    public function testGetRequestMethodReturnsPost(): void
    {
        $this->assertSame('POST', $this->request->getRequestMethod());
    }

    public function testSetAndGetGroupId(): void
    {
        $groupId = 'group_123';
        $this->request->setGroupId($groupId);
        $this->assertSame($groupId, $this->request->getGroupId());
    }

    public function testSetAndGetGroupIdWithNull(): void
    {
        $this->request->setGroupId(null);
        $this->assertNull($this->request->getGroupId());
    }

    public function testSetAndGetGroupName(): void
    {
        $groupName = '重要客户';
        $this->request->setGroupName($groupName);
        $this->assertSame($groupName, $this->request->getGroupName());
    }

    public function testSetAndGetGroupNameWithNull(): void
    {
        $this->request->setGroupName(null);
        $this->assertNull($this->request->getGroupName());
    }

    public function testSetAndGetTagList(): void
    {
        $tagList = [
            ['name' => '标签1'],
            ['name' => '标签2'],
        ];
        $this->request->setTagList($tagList);
        $this->assertSame($tagList, $this->request->getTagList());
    }

    public function testSetAndGetOrder(): void
    {
        $order = 100;
        $this->request->setOrder($order);
        $this->assertSame($order, $this->request->getOrder());
    }

    public function testSetAndGetOrderWithNull(): void
    {
        $this->request->setOrder(null);
        $this->assertNull($this->request->getOrder());
    }

    public function testSetAndGetAgentId(): void
    {
        $agentId = 1000002;
        $this->request->setAgentId($agentId);
        $this->assertSame($agentId, $this->request->getAgentId());
    }

    public function testSetAndGetAgentIdWithNull(): void
    {
        $this->request->setAgentId(null);
        $this->assertNull($this->request->getAgentId());
    }

    public function testGetRequestOptionsWithValidTagList(): void
    {
        $this->request->setTagList([
            ['name' => '标签1'],
            ['name' => '标签2'],
        ]);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);
        $this->assertArrayHasKey('tag', $options['json']);
        $this->assertIsArray($options['json']['tag']);
        $this->assertCount(2, $options['json']['tag']);
    }

    public function testGetRequestOptionsWithAllParameters(): void
    {
        $this->request->setGroupId('group_123');
        $this->request->setGroupName('测试组');
        $this->request->setOrder(100);
        $this->request->setAgentId(1000002);
        $this->request->setTagList([
            ['name' => '标签1'],
        ]);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);
        $json = $options['json'];

        $this->assertArrayHasKey('group_id', $json);
        $this->assertArrayHasKey('group_name', $json);
        $this->assertArrayHasKey('order', $json);
        $this->assertArrayHasKey('agentid', $json);
        $this->assertArrayHasKey('tag', $json);

        $this->assertSame('group_123', $json['group_id']);
        $this->assertSame('测试组', $json['group_name']);
        $this->assertSame(100, $json['order']);
        $this->assertSame(1000002, $json['agentid']);
    }

    public function testGetRequestOptionsWithEmptyTagList(): void
    {
        $this->request->setTagList([]);
        $options = $this->request->getRequestOptions();

        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);
        $this->assertArrayHasKey('tag', $options['json']);
        $this->assertIsArray($options['json']['tag']);
        $this->assertEmpty($options['json']['tag']);
    }

    public function testGetRequestOptionsThrowsExceptionForMissingTagName(): void
    {
        $this->expectException(InvalidCorpTagRequestException::class);
        $this->expectExceptionMessage('缺少标签名');

        $this->request->setTagList([
            ['order' => 1],
        ]);
        $this->request->getRequestOptions();
    }

    public function testGetRequestOptionsThrowsExceptionForLongTagName(): void
    {
        $this->expectException(InvalidCorpTagRequestException::class);
        $this->expectExceptionMessage('标签名不得超过30个字符');

        $this->request->setTagList([
            ['name' => str_repeat('标', 31)],
        ]);
        $this->request->getRequestOptions();
    }

    public function testGetRequestOptionsThrowsExceptionForLongGroupName(): void
    {
        $this->expectException(InvalidCorpTagRequestException::class);
        $this->expectExceptionMessage('标签组名称不得超过30个字符');

        $this->request->setGroupName(str_repeat('组', 31));
        $this->request->setTagList([
            ['name' => '标签1'],
        ]);
        $this->request->getRequestOptions();
    }

    public function testGetRequestOptionsWithEdgeCaseTagName(): void
    {
        $this->request->setTagList([
            ['name' => str_repeat('标', 30)], // 正好30个字符
        ]);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertIsArray($options['json']);
        $this->assertArrayHasKey('tag', $options['json']);
        $this->assertIsArray($options['json']['tag']);
        $this->assertCount(1, $options['json']['tag']);
    }

    public function testGetRequestOptionsWithEdgeCaseGroupName(): void
    {
        $this->request->setGroupName(str_repeat('组', 30)); // 正好30个字符
        $this->request->setTagList([
            ['name' => '标签1'],
        ]);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertIsArray($options['json']);
        $this->assertArrayHasKey('group_name', $options['json']);
    }
}
