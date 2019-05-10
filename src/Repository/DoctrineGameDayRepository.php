<?php

namespace App\Repository;

use App\Entity\Championship;
use App\Entity\GameDay;
use App\Exception\GameDayNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GameDay|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameDay|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameDay[]    findAll()
 * @method GameDay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineGameDayRepository extends ServiceEntityRepository implements GameDayRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GameDay::class);
    }

    public function getAll(): array
    {
        return $this->findAll();
    }

    public function getById(int $gameDayId): GameDay
    {
        $gameDay = $this->find( $gameDayId );

        if( ! $gameDay ){
            throw new GameDayNotFound( $gameDayId );
        }

        return $gameDay;
    }

    public function getGameDayByNumber(Championship $championship, int $number): GameDay
    {
        $gameDay = $this->findOneBy([
            "championship" => $championship,
            "number" => $number
        ]);


        if( ! $gameDay ){
            throw new GameDayNotFound( 0 );
        }

        return $gameDay;
    }

    public function save(GameDay $gameDay): void
    {
        $this->getEntityManager()->persist( $gameDay );
        $this->getEntityManager()->flush();
    }
    public function remove(GameDay $gameDay): void
    {
        $this->getEntityManager()->remove( $gameDay );
        $this->getEntityManager()->flush();
    }
}
