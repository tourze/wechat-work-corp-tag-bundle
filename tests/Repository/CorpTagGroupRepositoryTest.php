<?php

namespace WechatWorkCorpTagBundle\Tests\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use WechatWorkCorpTagBundle\Entity\CorpTagGroup;
use WechatWorkCorpTagBundle\Repository\CorpTagGroupRepository;

/**
 * CorpTagGroupRepository 测试用例
 */
class CorpTagGroupRepositoryTest extends TestCase
{
    private CorpTagGroupRepository $repository;
    private ManagerRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->repository = new CorpTagGroupRepository($this->registry);
    }

    public function test_constructor_createsRepositoryCorrectly(): void
    {
        $this->assertInstanceOf(CorpTagGroupRepository::class, $this->repository);
    }

    public function test_inheritsFromServiceEntityRepository(): void
    {
        $reflection = new \ReflectionClass($this->repository);
        $parentClass = $reflection->getParentClass();
        
        $this->assertNotFalse($parentClass);
        $this->assertSame('ServiceEntityRepository', $parentClass->getShortName());
    }
}