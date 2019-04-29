<?php

namespace App\Repository;

use App\Entity\Championship;
use App\Exception\ChampionshipNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Championship|null find($id, $lockMode = null, $lockVersion = null)
 * @method Championship|null findOneBy(array $criteria, array $orderBy = null)
 * @method Championship[]    findAll()
 * @method Championship[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineChampionshipRepository extends ServiceEntityRepository implements ChampionshipRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Championship::class);
    }

    public function getAll(): array
    {
        return $this->findAll();
    }

    public function getById(int $championshipId): Championship
    {
        $championship = $this->find( $championshipId );

        if( ! $championship ){
            throw new ChampionshipNotFound( $championshipId );
        }

        return $championship;
    }

    public function save(Championship $championship): void
    {
        $this->getEntityManager()->persist( $championship );
        $this->getEntityManager()->flush();
    }

    public function remove(Championship $championship): void
    {
        $this->getEntityManager()->remove( $championship );
        $this->getEntityManager()->flush();
    }
}
