<?php

declare(strict_types=1);

namespace WechatWorkCorpTagBundle\Service;

use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use WechatWorkCorpTagBundle\Entity\CorpTagGroup;
use WechatWorkCorpTagBundle\Entity\CorpTagItem;

#[Autoconfigure(public: true)]
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(
        private LinkGeneratorInterface $linkGenerator,
    ) {
    }

    public function __invoke(ItemInterface $item): void
    {
        // 确保企微管理菜单存在
        if (null === $item->getChild('企微管理')) {
            $item->addChild('企微管理')
                ->setAttribute('icon', 'fas fa-wechat')
            ;
        }

        $wechatMenu = $item->getChild('企微管理');
        if (null === $wechatMenu) {
            return;
        }

        // 添加企业标签管理子菜单
        if (null === $wechatMenu->getChild('企业标签管理')) {
            $wechatMenu->addChild('企业标签管理')
                ->setAttribute('icon', 'fas fa-tags')
            ;
        }

        $tagMenu = $wechatMenu->getChild('企业标签管理');
        if (null === $tagMenu) {
            return;
        }

        $tagMenu->addChild('标签分组')
            ->setUri($this->linkGenerator->getCurdListPage(CorpTagGroup::class))
            ->setAttribute('icon', 'fas fa-layer-group')
        ;

        $tagMenu->addChild('标签项目')
            ->setUri($this->linkGenerator->getCurdListPage(CorpTagItem::class))
            ->setAttribute('icon', 'fas fa-tag')
        ;
    }
}
