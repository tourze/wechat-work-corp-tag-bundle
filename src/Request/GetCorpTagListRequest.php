<?php

namespace WechatWorkCorpTagBundle\Request;

use HttpClientBundle\Request\ApiRequest;
use WechatWorkBundle\Request\AgentAware;

/**
 * 获取企业标签库
 * 企业可通过此接口获取企业客户标签详情。
 *
 * 若tag_id和group_id均为空，则返回所有标签。
 * 同时传递tag_id和group_id时，忽略tag_id，仅以group_id作为过滤条件。
 *
 * @see https://developer.work.weixin.qq.com/document/path/92117#获取企业标签库
 */
class GetCorpTagListRequest extends ApiRequest
{
    use AgentAware;

    /**
     * @var array<int, string>|null 要查询的标签id
     */
    private ?array $tagId = null;

    /**
     * @var array<int, string>|null 要查询的标签组id，返回该标签组以及其下的所有标签信息
     */
    private ?array $groupId = null;

    public function getRequestPath(): string
    {
        return '/cgi-bin/externalcontact/get_corp_tag_list';
    }

    /**
     * @return array<string, mixed>
     */
    public function getRequestOptions(): array
    {
        $json = [];
        if (null !== $this->getTagId()) {
            $json['tag_id'] = $this->getTagId();
        }
        if (null !== $this->getGroupId()) {
            $json['group_id'] = $this->getGroupId();
        }

        return [
            'json' => $json,
        ];
    }

    public function getRequestMethod(): ?string
    {
        return 'POST';
    }

    /**
     * @return array<int, string>|null
     */
    public function getTagId(): ?array
    {
        return $this->tagId;
    }

    /**
     * @param array<int, string>|null $tagId
     */
    public function setTagId(?array $tagId): void
    {
        $this->tagId = $tagId;
    }

    /**
     * @return array<int, string>|null
     */
    public function getGroupId(): ?array
    {
        return $this->groupId;
    }

    /**
     * @param array<int, string>|null $groupId
     */
    public function setGroupId(?array $groupId): void
    {
        $this->groupId = $groupId;
    }
}
