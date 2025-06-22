<?php

namespace WechatWorkCorpTagBundle\Entity;

use Carbon\CarbonImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Attribute\Ignore;
use Tourze\DoctrineIpBundle\Attribute\CreateIpColumn;
use Tourze\DoctrineIpBundle\Attribute\UpdateIpColumn;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use Tourze\WechatWorkContracts\AgentInterface;
use Tourze\WechatWorkContracts\CorpInterface;
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
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[Ignore]
    #[ORM\ManyToOne(targetEntity: CorpInterface::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?CorpInterface $corp = null;

    #[Ignore]
    #[ORM\ManyToOne(targetEntity: CorpTagGroup::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CorpTagGroup $tagGroup = null;

    #[ORM\Column(type: Types::STRING, length: 120, options: ['comment' => '标签名'])]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: true, options: ['comment' => '远程ID'])]
    private ?string $remoteId = null;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '排序', 'default' => 0])]
    private ?int $sortNumber = null;

    #[Ignore]
    #[ORM\ManyToOne(targetEntity: AgentInterface::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?AgentInterface $agent = null;

    #[CreateIpColumn]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '创建时IP'])]
    private ?string $createdFromIp = null;

    #[UpdateIpColumn]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '更新时IP'])]
    private ?string $updatedFromIp = null;

    public function __toString(): string
    {
        if ($this->getId() === null) {
            return '';
        }

        return "{$this->getTagGroup()->getName()}-{$this->getName()}";
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRemoteId(): ?string
    {
        return $this->remoteId;
    }

    public function setRemoteId(?string $remoteId): self
    {
        $this->remoteId = $remoteId;

        return $this;
    }

    public function getTagGroup(): ?CorpTagGroup
    {
        return $this->tagGroup;
    }

    public function setTagGroup(?CorpTagGroup $tagGroup): self
    {
        $this->tagGroup = $tagGroup;

        return $this;
    }

    public function getCorp(): ?CorpInterface
    {
        return $this->corp;
    }

    public function setCorp(?CorpInterface $corp): self
    {
        $this->corp = $corp;

        return $this;
    }

    public function getSortNumber(): ?int
    {
        return $this->sortNumber;
    }

    public function setSortNumber(int $sortNumber): self
    {
        $this->sortNumber = $sortNumber;

        return $this;
    }

    public function getAgent(): ?AgentInterface
    {
        return $this->agent;
    }

    public function setAgent(?AgentInterface $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    public function setCreatedFromIp(?string $createdFromIp): self
    {
        $this->createdFromIp = $createdFromIp;

        return $this;
    }

    public function getCreatedFromIp(): ?string
    {
        return $this->createdFromIp;
    }

    public function setUpdatedFromIp(?string $updatedFromIp): self
    {
        $this->updatedFromIp = $updatedFromIp;

        return $this;
    }

    public function getUpdatedFromIp(): ?string
    {
        return $this->updatedFromIp;
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
        if ($this->getRemoteId() !== null) {
            // 更新
            $request = new EditCorpTagRequest();
            $request->setAgent($this->getAgent());
            $request->setId($this->getRemoteId());
            $request->setName($this->getName());
            $request->setOrder($this->getSortNumber());
            $res = $mediaService->request($request);
            $logger->debug('更新企业标签', [
                'item' => $this,
                'res' => $res,
            ]);
        } else {
            $request = new AddCorpTagRequest();
            $request->setAgent($this->getAgent());
            // 创建
            // 有可能分组还没创建的喔
            if ($this->getTagGroup()->getRemoteId() === null) {
                $request->setGroupId($this->getTagGroup()->getRemoteId());
            }
            $request->setGroupName($this->getTagGroup()->getName());
            $request->setOrder($this->getTagGroup()->getSortNumber());
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
            if (isset($res['tag_group'])) {
                // 补充分组信息
                if ($this->getTagGroup()->getRemoteId() === null) {
                    $this->getTagGroup()->setRemoteId($res['tag_group']['group_id']);
                    $entityManager->persist($this->getTagGroup());
                    $entityManager->flush();
                }

                foreach ($res['tag_group']['tag'] as $tag) {
                    if ($tag['name'] === $this->getName()) {
                        $this->setRemoteId($tag['id']);
                        $this->setSortNumber($tag['order']);
                        $this->setCreateTime(CarbonImmutable::createFromTimestamp($tag['create_time'], date_default_timezone_get()));
                        $entityManager->persist($this);
                        $entityManager->flush();
                    }
                }
            }
        }
    }
}
