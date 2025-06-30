<?php

namespace WechatWorkCorpTagBundle\Tests\Request;

use PHPUnit\Framework\TestCase;
use Tourze\JsonRPC\Core\Exception\ApiException;
use WechatWorkCorpTagBundle\Request\AddCorpTagRequest;

/**
 * AddCorpTagRequest 测试用例
 */
class AddCorpTagRequestTest extends TestCase
{
    private AddCorpTagRequest $request;

    protected function setUp(): void
    {
        $this->request = new AddCorpTagRequest();
    }

    public function test_getRequestPath_returnsCorrectPath(): void
    {
        $this->assertSame('/cgi-bin/externalcontact/add_corp_tag', $this->request->getRequestPath());
    }

    public function test_getRequestMethod_returnsPost(): void
    {
        $this->assertSame('POST', $this->request->getRequestMethod());
    }

    public function test_setAndGetGroupId(): void
    {
        $groupId = 'group_123';
        $this->request->setGroupId($groupId);
        $this->assertSame($groupId, $this->request->getGroupId());
    }

    public function test_setAndGetGroupIdWithNull(): void
    {
        $this->request->setGroupId(null);
        $this->assertNull($this->request->getGroupId());
    }

    public function test_setAndGetGroupName(): void
    {
        $groupName = '重要客户';
        $this->request->setGroupName($groupName);
        $this->assertSame($groupName, $this->request->getGroupName());
    }

    public function test_setAndGetGroupNameWithNull(): void
    {
        $this->request->setGroupName(null);
        $this->assertNull($this->request->getGroupName());
    }

    public function test_setAndGetTagList(): void
    {
        $tagList = [
            ['name' => '标签1'],
            ['name' => '标签2']
        ];
        $this->request->setTagList($tagList);
        $this->assertSame($tagList, $this->request->getTagList());
    }

    public function test_setAndGetOrder(): void
    {
        $order = 100;
        $this->request->setOrder($order);
        $this->assertSame($order, $this->request->getOrder());
    }

    public function test_setAndGetOrderWithNull(): void
    {
        $this->request->setOrder(null);
        $this->assertNull($this->request->getOrder());
    }

    public function test_setAndGetAgentId(): void
    {
        $agentId = 1000002;
        $this->request->setAgentId($agentId);
        $this->assertSame($agentId, $this->request->getAgentId());
    }

    public function test_setAndGetAgentIdWithNull(): void
    {
        $this->request->setAgentId(null);
        $this->assertNull($this->request->getAgentId());
    }

    public function test_getRequestOptions_withValidTagList(): void
    {
        $this->request->setTagList([
            ['name' => '标签1'],
            ['name' => '标签2']
        ]);

        $options = $this->request->getRequestOptions();
        
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertArrayHasKey('tag', $options['json']);
        $this->assertCount(2, $options['json']['tag']);
    }

    public function test_getRequestOptions_withAllParameters(): void
    {
        $this->request->setGroupId('group_123');
        $this->request->setGroupName('测试组');
        $this->request->setOrder(100);
        $this->request->setAgentId(1000002);
        $this->request->setTagList([
            ['name' => '标签1']
        ]);

        $options = $this->request->getRequestOptions();
        
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
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

    public function test_getRequestOptions_throwsExceptionForInvalidTagFormat(): void
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('标签格式错误');

        $this->request->setTagList(['invalid_tag']);
        $this->request->getRequestOptions();
    }

    public function test_getRequestOptions_throwsExceptionForMissingTagName(): void
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('缺少标签名');

        $this->request->setTagList([
            ['order' => 1]
        ]);
        $this->request->getRequestOptions();
    }

    public function test_getRequestOptions_throwsExceptionForLongTagName(): void
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('标签名不得超过30个字符');

        $this->request->setTagList([
            ['name' => str_repeat('标', 31)]
        ]);
        $this->request->getRequestOptions();
    }

    public function test_getRequestOptions_throwsExceptionForLongGroupName(): void
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('标签组名称不得超过30个字符');

        $this->request->setGroupName(str_repeat('组', 31));
        $this->request->setTagList([
            ['name' => '标签1']
        ]);
        $this->request->getRequestOptions();
    }

    public function test_getRequestOptions_withEdgeCaseTagName(): void
    {
        $this->request->setTagList([
            ['name' => str_repeat('标', 30)] // 正好30个字符
        ]);

        $options = $this->request->getRequestOptions();
        
        $this->assertIsArray($options);
        $this->assertCount(1, $options['json']['tag']);
    }

    public function test_getRequestOptions_withEdgeCaseGroupName(): void
    {
        $this->request->setGroupName(str_repeat('组', 30)); // 正好30个字符
        $this->request->setTagList([
            ['name' => '标签1']
        ]);

        $options = $this->request->getRequestOptions();
        
        $this->assertIsArray($options);
        $this->assertArrayHasKey('group_name', $options['json']);
    }
}