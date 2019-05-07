<?php


namespace App\Repository;


use App\Entity\Account;
use App\Exception\AccountNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DoctrineAccountRepository extends ServiceEntityRepository implements AccountRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function getAll(): array
    {
        return $this->findAll();
    }

    public function getById(int $accountId): Account
    {
        $account = $this->find( $accountId );

        if( ! $account ){
            throw new AccountNotFound( $accountId );
        }

        return $account;
    }

    public function save(Account $account): void
    {
        $this->getEntityManager()->persist( $account );
        $this->getEntityManager()->flush();
    }

    public function remove(Account $account): void
    {
        $this->getEntityManager()->remove( $account );
        $this->getEntityManager()->flush();
    }
}