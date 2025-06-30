<?php

namespace WechatWorkCorpTagBundle\Tests\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use WechatWorkCorpTagBundle\Entity\CorpTagItem;
use WechatWorkCorpTagBundle\Repository\CorpTagItemRepository;

/**
 * CorpTagItemRepository 测试用例
 */
class CorpTagItemRepositoryTest extends TestCase
{
    private CorpTagItemRepository $repository;
    private ManagerRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->repository = new CorpTagItemRepository($this->registry);
    }

    public function test_constructor_createsRepositoryCorrectly(): void
    {
        $this->assertInstanceOf(CorpTagItemRepository::class, $this->repository);
    }

    public function test_inheritsFromServiceEntityRepository(): void
    {
        $reflection = new \ReflectionClass($this->repository);
        $parentClass = $reflection->getParentClass();
        
        $this->assertNotFalse($parentClass);
        $this->assertSame('ServiceEntityRepository', $parentClass->getShortName());
    }
}