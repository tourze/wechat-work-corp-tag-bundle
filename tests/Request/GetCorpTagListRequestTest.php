<?php

namespace WechatWorkCorpTagBundle\Tests\Request;

use PHPUnit\Framework\TestCase;
use WechatWorkCorpTagBundle\Request\GetCorpTagListRequest;

/**
 * GetCorpTagListRequest 测试用例
 */
class GetCorpTagListRequestTest extends TestCase
{
    private GetCorpTagListRequest $request;

    protected function setUp(): void
    {
        $this->request = new GetCorpTagListRequest();
    }

    public function test_getRequestPath_returnsCorrectPath(): void
    {
        $this->assertSame('/cgi-bin/externalcontact/get_corp_tag_list', $this->request->getRequestPath());
    }

    public function test_getRequestMethod_returnsPost(): void
    {
        $this->assertSame('POST', $this->request->getRequestMethod());
    }

    public function test_setAndGetTagId(): void
    {
        $tagIds = ['tag_001', 'tag_002', 'tag_003'];
        $this->request->setTagId($tagIds);
        $this->assertSame($tagIds, $this->request->getTagId());
    }

    public function test_setAndGetTagIdWithNull(): void
    {
        $this->request->setTagId(null);
        $this->assertNull($this->request->getTagId());
    }

    public function test_setAndGetGroupId(): void
    {
        $groupIds = ['group_001', 'group_002'];
        $this->request->setGroupId($groupIds);
        $this->assertSame($groupIds, $this->request->getGroupId());
    }

    public function test_setAndGetGroupIdWithNull(): void
    {
        $this->request->setGroupId(null);
        $this->assertNull($this->request->getGroupId());
    }

    public function test_getRequestOptions_withNoParameters(): void
    {
        $options = $this->request->getRequestOptions();
        
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);
        $this->assertEmpty($options['json']);
    }

    public function test_getRequestOptions_withTagIdOnly(): void
    {
        $tagIds = ['tag_001', 'tag_002'];
        $this->request->setTagId($tagIds);

        $options = $this->request->getRequestOptions();
        
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertArrayHasKey('tag_id', $options['json']);
        $this->assertSame($tagIds, $options['json']['tag_id']);
        $this->assertCount(1, $options['json']);
    }

    public function test_getRequestOptions_withGroupIdOnly(): void
    {
        $groupIds = ['group_001'];
        $this->request->setGroupId($groupIds);

        $options = $this->request->getRequestOptions();
        
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertArrayHasKey('group_id', $options['json']);
        $this->assertSame($groupIds, $options['json']['group_id']);
        $this->assertCount(1, $options['json']);
    }

    public function test_getRequestOptions_withBothTagIdAndGroupId(): void
    {
        $tagIds = ['tag_001', 'tag_002'];
        $groupIds = ['group_001', 'group_002'];
        
        $this->request->setTagId($tagIds);
        $this->request->setGroupId($groupIds);

        $options = $this->request->getRequestOptions();
        
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        
        $this->assertArrayHasKey('tag_id', $json);
        $this->assertArrayHasKey('group_id', $json);
        $this->assertSame($tagIds, $json['tag_id']);
        $this->assertSame($groupIds, $json['group_id']);
        $this->assertCount(2, $json);
    }

    public function test_setAndGetTagId_withEmptyArray(): void
    {
        $this->request->setTagId([]);
        $this->assertSame([], $this->request->getTagId());
    }

    public function test_setAndGetGroupId_withEmptyArray(): void
    {
        $this->request->setGroupId([]);
        $this->assertSame([], $this->request->getGroupId());
    }

    public function test_getRequestOptions_withEmptyArrays(): void
    {
        $this->request->setTagId([]);
        $this->request->setGroupId([]);

        $options = $this->request->getRequestOptions();
        
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        
        $this->assertArrayHasKey('tag_id', $json);
        $this->assertArrayHasKey('group_id', $json);
        $this->assertSame([], $json['tag_id']);
        $this->assertSame([], $json['group_id']);
    }

    public function test_edgeCases_largeArrays(): void
    {
        $largeTagIds = array_map(fn($i) => "tag_$i", range(1, 100));
        $largeGroupIds = array_map(fn($i) => "group_$i", range(1, 50));
        
        $this->request->setTagId($largeTagIds);
        $this->request->setGroupId($largeGroupIds);

        $options = $this->request->getRequestOptions();
        
        $this->assertIsArray($options);
        $json = $options['json'];
        $this->assertCount(100, $json['tag_id']);
        $this->assertCount(50, $json['group_id']);
    }

    public function test_setAndGetTagId_withMixedTypes(): void
    {
        $mixedIds = ['tag_001', 123, 'tag_003'];
        $this->request->setTagId($mixedIds);
        $this->assertSame($mixedIds, $this->request->getTagId());
    }

    public function test_setAndGetGroupId_withMixedTypes(): void
    {
        $mixedIds = ['group_001', 456, 'group_003'];
        $this->request->setGroupId($mixedIds);
        $this->assertSame($mixedIds, $this->request->getGroupId());
    }

    public function test_overwritePreviousValues(): void
    {
        $firstTagIds = ['tag_001'];
        $secondTagIds = ['tag_002', 'tag_003'];
        
        $this->request->setTagId($firstTagIds);
        $this->assertSame($firstTagIds, $this->request->getTagId());
        
        $this->request->setTagId($secondTagIds);
        $this->assertSame($secondTagIds, $this->request->getTagId());
    }
}