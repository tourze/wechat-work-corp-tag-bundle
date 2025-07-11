<?php

namespace WechatWorkCorpTagBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatWorkCorpTagBundle\Entity\CorpTagGroup;

/**
 * @method CorpTagGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method CorpTagGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method CorpTagGroup[]    findAll()
 * @method CorpTagGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CorpTagGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CorpTagGroup::class);
    }
}
