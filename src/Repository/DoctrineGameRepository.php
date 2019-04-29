<?php

namespace App\Repository;

use App\Entity\Game;
use App\Exception\GameNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineGameRepository extends ServiceEntityRepository implements GameRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function getAll(): array
    {
        return $this->findAll();
    }

    public function getById(int $gameId): Game
    {
        $game = $this->find( $gameId );

        if( ! $game ){
            throw new GameNotFound( $gameId );
        }

        return $game;
    }

    public function save(Game $game): void
    {
        $this->getEntityManager()->persist( $game );
        $this->getEntityManager()->flush();
    }

    public function remove(Game $game): void
    {
        $this->getEntityManager()->remove( $game );
        $this->getEntityManager()->flush();
    }
}
