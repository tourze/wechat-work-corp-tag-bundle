<?php

namespace WechatWorkCorpTagBundle\Tests\Request;

use HttpClientBundle\Tests\Request\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatWorkCorpTagBundle\Request\GetCorpTagListRequest;

/**
 * GetCorpTagListRequest 测试用例
 *
 * @internal
 */
#[CoversClass(GetCorpTagListRequest::class)]
final class GetCorpTagListRequestTest extends RequestTestCase
{
    private GetCorpTagListRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new GetCorpTagListRequest();
    }

    public function testGetRequestPathReturnsCorrectPath(): void
    {
        $this->assertSame('/cgi-bin/externalcontact/get_corp_tag_list', $this->request->getRequestPath());
    }

    public function testGetRequestMethodReturnsPost(): void
    {
        $this->assertSame('POST', $this->request->getRequestMethod());
    }

    public function testSetAndGetTagId(): void
    {
        $tagIds = ['tag_001', 'tag_002', 'tag_003'];
        $this->request->setTagId($tagIds);
        $this->assertSame($tagIds, $this->request->getTagId());
    }

    public function testSetAndGetTagIdWithNull(): void
    {
        $this->request->setTagId(null);
        $this->assertNull($this->request->getTagId());
    }

    public function testSetAndGetGroupId(): void
    {
        $groupIds = ['group_001', 'group_002'];
        $this->request->setGroupId($groupIds);
        $this->assertSame($groupIds, $this->request->getGroupId());
    }

    public function testSetAndGetGroupIdWithNull(): void
    {
        $this->request->setGroupId(null);
        $this->assertNull($this->request->getGroupId());
    }

    public function testGetRequestOptionsWithNoParameters(): void
    {
        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);
        $this->assertEmpty($options['json']);
    }

    public function testGetRequestOptionsWithTagIdOnly(): void
    {
        $tagIds = ['tag_001', 'tag_002'];
        $this->request->setTagId($tagIds);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertArrayHasKey('tag_id', $json);
        $this->assertSame($tagIds, $json['tag_id']);
        $this->assertCount(1, $json);
    }

    public function testGetRequestOptionsWithGroupIdOnly(): void
    {
        $groupIds = ['group_001'];
        $this->request->setGroupId($groupIds);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertArrayHasKey('group_id', $json);
        $this->assertSame($groupIds, $json['group_id']);
        $this->assertCount(1, $json);
    }

    public function testGetRequestOptionsWithBothTagIdAndGroupId(): void
    {
        $tagIds = ['tag_001', 'tag_002'];
        $groupIds = ['group_001', 'group_002'];

        $this->request->setTagId($tagIds);
        $this->request->setGroupId($groupIds);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);

        $this->assertArrayHasKey('tag_id', $json);
        $this->assertArrayHasKey('group_id', $json);
        $this->assertSame($tagIds, $json['tag_id']);
        $this->assertSame($groupIds, $json['group_id']);
        $this->assertCount(2, $json);
    }

    public function testSetAndGetTagIdWithEmptyArray(): void
    {
        $this->request->setTagId([]);
        $this->assertSame([], $this->request->getTagId());
    }

    public function testSetAndGetGroupIdWithEmptyArray(): void
    {
        $this->request->setGroupId([]);
        $this->assertSame([], $this->request->getGroupId());
    }

    public function testGetRequestOptionsWithEmptyArrays(): void
    {
        $this->request->setTagId([]);
        $this->request->setGroupId([]);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);
        $json = $options['json'];

        $this->assertArrayHasKey('tag_id', $json);
        $this->assertArrayHasKey('group_id', $json);
        $this->assertSame([], $json['tag_id']);
        $this->assertSame([], $json['group_id']);
    }

    public function testEdgeCasesLargeArrays(): void
    {
        $largeTagIds = array_map(fn ($i) => "tag_{$i}", range(1, 100));
        $largeGroupIds = array_map(fn ($i) => "group_{$i}", range(1, 50));

        $this->request->setTagId($largeTagIds);
        $this->request->setGroupId($largeGroupIds);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertIsArray($json['tag_id']);
        $this->assertIsArray($json['group_id']);
        $this->assertCount(100, $json['tag_id']);
        $this->assertCount(50, $json['group_id']);
    }

    public function testSetAndGetTagIdWithMixedTypes(): void
    {
        $mixedIds = ['tag_001', '123', 'tag_003'];
        $this->request->setTagId($mixedIds);
        $this->assertSame($mixedIds, $this->request->getTagId());
    }

    public function testSetAndGetGroupIdWithMixedTypes(): void
    {
        $mixedIds = ['group_001', '456', 'group_003'];
        $this->request->setGroupId($mixedIds);
        $this->assertSame($mixedIds, $this->request->getGroupId());
    }

    public function testOverwritePreviousValues(): void
    {
        $firstTagIds = ['tag_001'];
        $secondTagIds = ['tag_002', 'tag_003'];

        $this->request->setTagId($firstTagIds);
        $this->assertSame($firstTagIds, $this->request->getTagId());

        $this->request->setTagId($secondTagIds);
        $this->assertSame($secondTagIds, $this->request->getTagId());
    }
}
