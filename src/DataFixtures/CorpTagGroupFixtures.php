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

#[When(env: 'test')]
#[When(env: 'dev')]
class CorpTagGroupFixtures extends Fixture implements DependentFixtureInterface
{
    public const CORP_TAG_GROUP_REFERENCE_PREFIX = 'corp_tag_group_';
    public const CORP_TAG_GROUP_COUNT = 10;

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('zh_CN');
    }

    public function load(ObjectManager $manager): void
    {
        $corp = $this->getReference(CorpFixtures::CORP_1_REFERENCE, Corp::class);
        $agent = $this->getReference(AgentFixtures::AGENT_1_REFERENCE, Agent::class);

        for ($i = 0; $i < self::CORP_TAG_GROUP_COUNT; ++$i) {
            $corpTagGroup = $this->createCorpTagGroup($corp, $agent);
            $manager->persist($corpTagGroup);
            $this->addReference(self::CORP_TAG_GROUP_REFERENCE_PREFIX . $i, $corpTagGroup);
        }

        $manager->flush();
    }

    private function createCorpTagGroup(Corp $corp, Agent $agent): CorpTagGroup
    {
        $corpTagGroup = new CorpTagGroup();
        $words = $this->faker->words(2, true);
        $corpTagGroup->setName(is_array($words) ? implode('', $words) . '标签组' : $words . '标签组');
        $corpTagGroup->setRemoteId($this->faker->regexify('[a-zA-Z0-9]{32}'));
        $corpTagGroup->setSortNumber($this->faker->numberBetween(1, 100));
        $corpTagGroup->setCorp($corp);
        $corpTagGroup->setAgent($agent);

        return $corpTagGroup;
    }

    public function getDependencies(): array
    {
        return [
            CorpFixtures::class,
            AgentFixtures::class,
        ];
    }
}
