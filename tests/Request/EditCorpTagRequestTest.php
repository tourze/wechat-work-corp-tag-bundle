<?php

namespace WechatWorkCorpTagBundle\Tests\Request;

use HttpClientBundle\Tests\Request\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatWorkCorpTagBundle\Request\EditCorpTagRequest;

/**
 * EditCorpTagRequest 测试用例
 *
 * @internal
 */
#[CoversClass(EditCorpTagRequest::class)]
final class EditCorpTagRequestTest extends RequestTestCase
{
    private EditCorpTagRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new EditCorpTagRequest();
    }

    public function testGetRequestPathReturnsCorrectPath(): void
    {
        $this->assertSame('/cgi-bin/externalcontact/edit_corp_tag', $this->request->getRequestPath());
    }

    public function testGetRequestMethodReturnsPost(): void
    {
        $this->assertSame('POST', $this->request->getRequestMethod());
    }

    public function testSetAndGetId(): void
    {
        $id = 'tag_123';
        $this->request->setId($id);
        $this->assertSame($id, $this->request->getId());
    }

    public function testSetAndGetName(): void
    {
        $name = '修改后的标签名';
        $this->request->setName($name);
        $this->assertSame($name, $this->request->getName());
    }

    public function testSetAndGetNameWithNull(): void
    {
        $this->request->setName(null);
        $this->assertNull($this->request->getName());
    }

    public function testSetAndGetOrder(): void
    {
        $order = 200;
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
        $agentId = 1000003;
        $this->request->setAgentId($agentId);
        $this->assertSame($agentId, $this->request->getAgentId());
    }

    public function testSetAndGetAgentIdWithNull(): void
    {
        $this->request->setAgentId(null);
        $this->assertNull($this->request->getAgentId());
    }

    public function testGetRequestOptionsWithRequiredParametersOnly(): void
    {
        $this->request->setId('tag_123');

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertArrayHasKey('id', $json);
        $this->assertSame('tag_123', $json['id']);
        $this->assertCount(1, $json);
    }

    public function testGetRequestOptionsWithAllParameters(): void
    {
        $this->request->setId('tag_123');
        $this->request->setName('新标签名');
        $this->request->setOrder(150);
        $this->request->setAgentId(1000003);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);
        $json = $options['json'];

        $this->assertArrayHasKey('id', $json);
        $this->assertArrayHasKey('name', $json);
        $this->assertArrayHasKey('order', $json);
        $this->assertArrayHasKey('agentid', $json);

        $this->assertSame('tag_123', $json['id']);
        $this->assertSame('新标签名', $json['name']);
        $this->assertSame(150, $json['order']);
        $this->assertSame(1000003, $json['agentid']);
    }

    public function testGetRequestOptionsWithNullOptionalParameters(): void
    {
        $this->request->setId('tag_456');
        $this->request->setName(null);
        $this->request->setOrder(null);
        $this->request->setAgentId(null);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);

        $this->assertArrayHasKey('id', $json);
        $this->assertArrayNotHasKey('name', $json);
        $this->assertArrayNotHasKey('order', $json);
        $this->assertArrayNotHasKey('agentid', $json);
        $this->assertCount(1, $json);
    }

    public function testEdgeCasesExtremeValues(): void
    {
        $this->request->setId(str_repeat('x', 100));
        $this->request->setName(str_repeat('标', 30));
        $this->request->setOrder(PHP_INT_MAX);
        $this->request->setAgentId(PHP_INT_MAX);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertIsArray($options['json']);
        $json = $options['json'];
        $this->assertSame(str_repeat('x', 100), $json['id']);
        $this->assertSame(str_repeat('标', 30), $json['name']);
        $this->assertSame(PHP_INT_MAX, $json['order']);
        $this->assertSame(PHP_INT_MAX, $json['agentid']);
    }

    public function testSetAndGetOrderWithZero(): void
    {
        $this->request->setOrder(0);
        $this->assertSame(0, $this->request->getOrder());
    }

    public function testSetAndGetAgentIdWithZero(): void
    {
        $this->request->setAgentId(0);
        $this->assertSame(0, $this->request->getAgentId());
    }

    public function testGetRequestOptionsWithEmptyName(): void
    {
        $this->request->setId('tag_789');
        $this->request->setName('');

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertIsArray($options['json']);
        $json = $options['json'];
        $this->assertArrayHasKey('name', $json);
        $this->assertSame('', $json['name']);
    }
}
