<?php

namespace WechatWorkCorpTagBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineIpBundle\Traits\IpTraceableAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use Tourze\WechatWorkContracts\AgentInterface;
use Tourze\WechatWorkContracts\CorpInterface;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkCorpTagBundle\Repository\CorpTagGroupRepository;

/**
 * @see https://developer.work.weixin.qq.com/document/path/92117
 */
#[ORM\Entity(repositoryClass: CorpTagGroupRepository::class)]
#[ORM\Table(name: 'wechat_work_corp_tag_group', options: ['comment' => '企业标签分组'])]
class CorpTagGroup implements \Stringable
{
    use TimestampableAware;
    use BlameableAware;
    use IpTraceableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Corp::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?CorpInterface $corp = null;

    #[ORM\ManyToOne(targetEntity: Agent::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?AgentInterface $agent = null;

    #[ORM\Column(type: Types::STRING, length: 120, options: ['comment' => '分组名'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 120)]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: true, options: ['comment' => '远程ID'])]
    #[Assert\Length(max: 64)]
    private ?string $remoteId = null;

    /**
     * @var Collection<int, CorpTagItem>
     */
    #[ORM\OneToMany(mappedBy: 'tagGroup', targetEntity: CorpTagItem::class)]
    private Collection $items;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '排序', 'default' => 0])]
    #[Assert\PositiveOrZero]
    private ?int $sortNumber = null;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, CorpTagItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(CorpTagItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setTagGroup($this);
        }

        return $this;
    }

    public function removeItem(CorpTagItem $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getTagGroup() === $this) {
                $item->setTagGroup(null);
            }
        }

        return $this;
    }

    public function getCorp(): ?CorpInterface
    {
        return $this->corp;
    }

    public function setCorp(?CorpInterface $corp): void
    {
        $this->corp = $corp;
    }

    public function getAgent(): ?AgentInterface
    {
        return $this->agent;
    }

    public function setAgent(?AgentInterface $agent): void
    {
        $this->agent = $agent;
    }

    public function getSortNumber(): ?int
    {
        return $this->sortNumber;
    }

    public function setSortNumber(int $sortNumber): void
    {
        $this->sortNumber = $sortNumber;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
