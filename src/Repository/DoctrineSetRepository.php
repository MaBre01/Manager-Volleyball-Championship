<?php

namespace App\Repository;

use App\Entity\Set;
use App\Exception\SetNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Set|null find($id, $lockMode = null, $lockVersion = null)
 * @method Set|null findOneBy(array $criteria, array $orderBy = null)
 * @method Set[]    findAll()
 * @method Set[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineSetRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Set::class);
    }

    public function getAll(): array
    {
        return $this->findAll();
    }

    public function getById(int $setId): Set
    {
        $set = $this->find( $setId );

        if( ! $set ){
            throw new SetNotFound( $setId );
        }

        return $set;
    }

    public function save(Set $set): void
    {
        $this->getEntityManager()->persist( $set );
        $this->getEntityManager()->flush();
    }

    public function remove(Set $set): void
    {
        $this->getEntityManager()->remove( $set );
        $this->getEntityManager()->flush();
    }
}
