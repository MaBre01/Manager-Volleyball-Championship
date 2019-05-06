<?php

namespace App\Repository;

use App\Entity\SetPoint;
use App\Exception\SetNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SetPoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method SetPoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method SetPoint[]    findAll()
 * @method SetPoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineSetRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SetPoint::class);
    }

    public function getAll(): array
    {
        return $this->findAll();
    }

    public function getById(int $setId): SetPoint
    {
        $set = $this->find( $setId );

        if( ! $set ){
            throw new SetNotFound( $setId );
        }

        return $set;
    }

    public function save(SetPoint $set): void
    {
        $this->getEntityManager()->persist( $set );
        $this->getEntityManager()->flush();
    }

    public function remove(SetPoint $set): void
    {
        $this->getEntityManager()->remove( $set );
        $this->getEntityManager()->flush();
    }
}
