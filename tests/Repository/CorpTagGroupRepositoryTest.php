<?php

namespace WechatWorkCorpTagBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkCorpTagBundle\Entity\CorpTagGroup;
use WechatWorkCorpTagBundle\Repository\CorpTagGroupRepository;

/**
 * @internal
 */
#[CoversClass(CorpTagGroupRepository::class)]
#[RunTestsInSeparateProcesses]
final class CorpTagGroupRepositoryTest extends AbstractRepositoryTestCase
{
    private ?Corp $testCorp = null;

    private ?Agent $testAgent = null;

    protected function onSetUp(): void
    {
        self::cleanDatabase();
        $this->setupTestData();
    }

    private function setupTestData(): void
    {
        $entityManager = self::getEntityManager();

        $this->testCorp = new Corp();
        $this->testCorp->setName('Test Corp');
        $this->testCorp->setCorpId('test_corp_' . uniqid());
        $this->testCorp->setFromProvider(false);
        $this->testCorp->setCorpSecret('test_secret_' . uniqid());
        $entityManager->persist($this->testCorp);

        $this->testAgent = new Agent();
        $this->testAgent->setName('Test Agent');
        $this->testAgent->setAgentId('agent_' . uniqid());
        $this->testAgent->setSecret('agent_secret_' . uniqid());
        $this->testAgent->setToken('agent_token_' . uniqid());
        $this->testAgent->setEncodingAESKey('aes_key_' . uniqid());
        $this->testAgent->setCorp($this->testCorp);
        $entityManager->persist($this->testAgent);

        $entityManager->flush();
    }

    protected function createNewEntity(): object
    {
        $entity = new CorpTagGroup();
        $entity->setName('Test CorpTagGroup ' . uniqid());
        $entity->setCorp($this->testCorp);
        $entity->setAgent($this->testAgent);
        $entity->setSortNumber(0);

        return $entity;
    }

    /**
     * @return ServiceEntityRepository<CorpTagGroup>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return self::getService(CorpTagGroupRepository::class);
    }
}
