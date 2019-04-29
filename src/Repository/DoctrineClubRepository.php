<?php

namespace App\Repository;

use App\Entity\Club;
use App\Exception\ClubNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Club|null find($id, $lockMode = null, $lockVersion = null)
 * @method Club|null findOneBy(array $criteria, array $orderBy = null)
 * @method Club[]    findAll()
 * @method Club[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineClubRepository extends ServiceEntityRepository implements ClubRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Club::class);
    }

    public function getAll(): array
    {
        return $this->findAll();
    }

    public function getById(int $clubId): Club
    {
        $club = $this->find( $clubId );

        if( ! $club ){
            throw new ClubNotFound( $clubId );
        }

        return $club;
    }

    public function save(Club $club): void
    {
        $this->getEntityManager()->persist( $club );
        $this->getEntityManager()->flush();
    }

    public function remove(Club $club): void
    {
        $this->getEntityManager()->remove( $club );
        $this->getEntityManager()->flush();
    }
}
