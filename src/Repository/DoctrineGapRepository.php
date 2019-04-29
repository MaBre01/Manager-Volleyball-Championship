<?php

namespace App\Repository;

use App\Entity\Gap;
use App\Exception\GapNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Gap|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gap|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gap[]    findAll()
 * @method Gap[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineGapRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Gap::class);
    }

    public function getAll(): array
    {
        return $this->findAll();
    }

    public function getById(int $gapId): Gap
    {
        $gap = $this->find( $gapId );

        if( ! $gap ){
            throw new GapNotFound( $gapId );
        }

        return $gap;
    }

    public function save(Gap $gap): void
    {
        $this->getEntityManager()->persist( $gap );
        $this->getEntityManager()->flush();
    }

    public function remove(Gap $gap): void
    {
        $this->getEntityManager()->remove( $gap );
        $this->getEntityManager()->flush();
    }
}
