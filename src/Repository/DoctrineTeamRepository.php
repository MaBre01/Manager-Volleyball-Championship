<?php

namespace App\Repository;

use App\Entity\Team;
use App\Exception\TeamNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineTeamRepository extends ServiceEntityRepository implements TeamRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function getAll(): array
    {
        return $this->findAll();
    }

    public function getById(int $teamId): Team
    {
        $team = $this->find( $teamId );

        if( ! $team ){
            throw new TeamNotFound( $teamId );
        }

        return $team;
    }

    public function save(Team $team): void
    {
        $this->getEntityManager()->persist( $team );
        $this->getEntityManager()->flush();
    }

    public function remove(Team $team): void
    {
        $this->getEntityManager()->remove( $team );
        $this->getEntityManager()->flush();
    }
}
