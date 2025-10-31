<?php

namespace WechatWorkCorpTagBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatWorkBundle\DataFixtures\AgentFixtures;
use WechatWorkBundle\DataFixtures\CorpFixtures;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkCorpTagBundle\Entity\CorpTagGroup;
use WechatWorkCorpTagBundle\Entity\CorpTagItem;

#[When(env: 'test')]
#[When(env: 'dev')]
class CorpTagItemFixtures extends Fixture implements DependentFixtureInterface
{
    public const CORP_TAG_ITEM_REFERENCE_PREFIX = 'corp_tag_item_';
    public const CORP_TAG_ITEM_COUNT = 30;

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('zh_CN');
    }

    public function load(ObjectManager $manager): void
    {
        $corp = $this->getReference(CorpFixtures::CORP_1_REFERENCE, Corp::class);
        $agent = $this->getReference(AgentFixtures::AGENT_1_REFERENCE, Agent::class);

        for ($i = 0; $i < self::CORP_TAG_ITEM_COUNT; ++$i) {
            $groupIndex = $i % CorpTagGroupFixtures::CORP_TAG_GROUP_COUNT;
            $tagGroup = $this->getReference(
                CorpTagGroupFixtures::CORP_TAG_GROUP_REFERENCE_PREFIX . $groupIndex,
                CorpTagGroup::class
            );

            $corpTagItem = $this->createCorpTagItem($tagGroup, $corp, $agent, $i);
            $manager->persist($corpTagItem);
            $this->addReference(self::CORP_TAG_ITEM_REFERENCE_PREFIX . $i, $corpTagItem);
        }

        $manager->flush();
    }

    private function createCorpTagItem(CorpTagGroup $tagGroup, Corp $corp, Agent $agent, int $index): CorpTagItem
    {
        $corpTagItem = new CorpTagItem();
        $corpTagItem->setName($this->faker->word() . '标签_' . $index);
        $corpTagItem->setRemoteId($this->faker->regexify('[a-zA-Z0-9]{32}'));
        $corpTagItem->setSortNumber($this->faker->numberBetween(1, 100));
        $corpTagItem->setTagGroup($tagGroup);
        $corpTagItem->setCorp($corp);
        $corpTagItem->setAgent($agent);

        return $corpTagItem;
    }

    public function getDependencies(): array
    {
        return [
            CorpFixtures::class,
            AgentFixtures::class,
            CorpTagGroupFixtures::class,
        ];
    }
}
