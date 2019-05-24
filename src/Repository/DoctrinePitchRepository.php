<?php

namespace App\Repository;

use App\Entity\Pitch;
use App\Exception\PitchNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Pitch|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pitch|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pitch[]    findAll()
 * @method Pitch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrinePitchRepository extends ServiceEntityRepository implements PitchRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Pitch::class);
    }

    public function getAll(): array
    {
        return $this->findAll();
    }

    public function getById(int $pitchId): Pitch
    {
        $pitch = $this->find( $pitchId );

        if( ! $pitch ){
            throw new PitchNotFound( $pitchId );
        }

        return $pitch;
    }

    public function save(Pitch $pitch): void
    {
        $this->getEntityManager()->persist( $pitch );
        $this->getEntityManager()->flush();
    }

    public function remove(Pitch $pitch): void
    {
        $this->getEntityManager()->remove( $pitch );
        $this->getEntityManager()->flush();
    }
}
