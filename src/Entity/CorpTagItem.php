<?php

namespace WechatWorkCorpTagBundle\Entity;

use Carbon\CarbonImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineIpBundle\Traits\IpTraceableAware;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use Tourze\WechatWorkContracts\AgentInterface;
use Tourze\WechatWorkContracts\CorpInterface;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkBundle\Service\WorkService;
use WechatWorkCorpTagBundle\Repository\CorpTagGroupRepository;
use WechatWorkCorpTagBundle\Repository\CorpTagItemRepository;
use WechatWorkCorpTagBundle\Request\AddCorpTagRequest;
use WechatWorkCorpTagBundle\Request\EditCorpTagRequest;

/**
 * @see https://developer.work.weixin.qq.com/document/path/92117
 */
#[ORM\Entity(repositoryClass: CorpTagItemRepository::class)]
#[ORM\Table(name: 'wechat_work_corp_tag_item', options: ['comment' => '企业标签项目'])]
#[ORM\UniqueConstraint(name: 'wechat_work_corp_tag_item_uniq_idx', columns: ['tag_group_id', 'name'])]
class CorpTagItem implements \Stringable
{
    use TimestampableAware;
    use BlameableAware;
    use SnowflakeKeyAware;
    use IpTraceableAware;

    #[Ignore]
    #[ORM\ManyToOne(targetEntity: Corp::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?CorpInterface $corp = null;

    #[Ignore]
    #[ORM\ManyToOne(targetEntity: CorpTagGroup::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CorpTagGroup $tagGroup = null;

    #[ORM\Column(type: Types::STRING, length: 120, options: ['comment' => '标签名'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 120)]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: true, options: ['comment' => '远程ID'])]
    #[Assert\Length(max: 64)]
    private ?string $remoteId = null;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '排序', 'default' => 0])]
    #[Assert\PositiveOrZero]
    private ?int $sortNumber = null;

    #[Ignore]
    #[ORM\ManyToOne(targetEntity: Agent::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?AgentInterface $agent = null;

    public function __toString(): string
    {
        if (null === $this->getId()) {
            return '';
        }

        return "{$this->getTagGroup()?->getName()}-{$this->getName()}";
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getRemoteId(): ?string
    {
        return $this->remoteId;
    }

    public function setRemoteId(?string $remoteId): void
    {
        $this->remoteId = $remoteId;
    }

    public function getTagGroup(): ?CorpTagGroup
    {
        return $this->tagGroup;
    }

    public function setTagGroup(?CorpTagGroup $tagGroup): void
    {
        $this->tagGroup = $tagGroup;
    }

    public function getCorp(): ?CorpInterface
    {
        return $this->corp;
    }

    public function setCorp(?CorpInterface $corp): void
    {
        $this->corp = $corp;
    }

    public function getSortNumber(): ?int
    {
        return $this->sortNumber;
    }

    public function setSortNumber(int $sortNumber): void
    {
        $this->sortNumber = $sortNumber;
    }

    public function getAgent(): ?AgentInterface
    {
        return $this->agent;
    }

    public function setAgent(?AgentInterface $agent): void
    {
        $this->agent = $agent;
    }

    /**
     * 编辑后，同步到远程.
     */
    public function syncToRemote(
        CorpTagGroupRepository $tagGroupRepository,
        CorpTagItemRepository $itemRepository,
        WorkService $mediaService,
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
    ): void {
        if (null !== $this->getRemoteId()) {
            $this->updateRemoteTag($mediaService, $logger);
        } else {
            $this->createRemoteTag($mediaService, $logger, $entityManager);
        }
    }

    private function updateRemoteTag(WorkService $mediaService, LoggerInterface $logger): void
    {
        $request = new EditCorpTagRequest();
        $request->setAgent($this->getAgent());
        $request->setId((string) $this->getRemoteId());
        $request->setName($this->getName());
        $request->setOrder($this->getSortNumber());
        $res = $mediaService->request($request);
        $logger->debug('更新企业标签', [
            'item' => $this,
            'res' => $res,
        ]);
    }

    private function createRemoteTag(
        WorkService $mediaService,
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
    ): void {
        $request = new AddCorpTagRequest();
        $request->setAgent($this->getAgent());

        $tagGroup = $this->getTagGroup();
        if (null !== $tagGroup && null !== $tagGroup->getRemoteId()) {
            $request->setGroupId($tagGroup->getRemoteId());
        }
        if (null !== $tagGroup) {
            $request->setGroupName($tagGroup->getName());
            $request->setOrder($tagGroup->getSortNumber());
        }
        $request->setTagList([
            [
                'name' => $this->getName(),
                'order' => $this->getSortNumber(),
            ],
        ]);

        $res = $mediaService->request($request);
        $logger->debug('新增企业标签', [
            'item' => $this,
            'res' => $res,
        ]);

        if (is_array($res) && isset($res['tag_group']) && is_array($res['tag_group'])) {
            /** @var array<string, mixed> $tagGroupData */
            $tagGroupData = $res['tag_group'];
            $this->processTagGroupResponse($tagGroupData, $entityManager);
        }
    }

    /**
     * @param array<string, mixed> $tagGroup
     */
    private function processTagGroupResponse(array $tagGroup, EntityManagerInterface $entityManager): void
    {
        $tagGroupEntity = $this->getTagGroup();
        if (null === $tagGroupEntity?->getRemoteId() && null !== $tagGroupEntity) {
            if (isset($tagGroup['group_id'])) {
                $remoteId = $tagGroup['group_id'];
                if (is_string($remoteId) || is_numeric($remoteId)) {
                    $tagGroupEntity->setRemoteId((string) $remoteId);
                    $entityManager->persist($tagGroupEntity);
                    $entityManager->flush();
                }
            }
        }

        if (isset($tagGroup['tag']) && is_array($tagGroup['tag'])) {
            $this->processTagsResponse($tagGroup['tag'], $entityManager);
        }
    }

    /**
     * @param array<mixed> $tags
     */
    private function processTagsResponse(array $tags, EntityManagerInterface $entityManager): void
    {
        $currentTagName = $this->getName();

        foreach ($tags as $tag) {
            if (!$this->isValidTagData($tag, $currentTagName)) {
                continue;
            }

            /** @var array<string, mixed> $tagData */
            $tagData = $tag;
            $this->updateFromTagData($tagData, $entityManager);
            break;
        }
    }

    /**
     * @param mixed $tag
     */
    private function isValidTagData($tag, string $currentTagName): bool
    {
        return is_array($tag)
            && isset($tag['name'])
            && $tag['name'] === $currentTagName
            && isset($tag['id'])
            && isset($tag['order'])
            && isset($tag['create_time']);
    }

    /**
     * @param array<string, mixed> $tagData
     */
    private function updateFromTagData(array $tagData, EntityManagerInterface $entityManager): void
    {
        $tagId = $tagData['id'];
        if (is_string($tagId) || is_numeric($tagId)) {
            $this->setRemoteId((string) $tagId);
        }

        $order = $tagData['order'];
        if (is_numeric($order)) {
            $this->setSortNumber((int) $order);
        }

        $createTime = $tagData['create_time'];
        if (is_numeric($createTime)) {
            $this->setCreateTime(CarbonImmutable::createFromTimestamp(
                (int) $createTime,
                date_default_timezone_get()
            ));
        }

        $entityManager->persist($this);
        $entityManager->flush();
    }
}
