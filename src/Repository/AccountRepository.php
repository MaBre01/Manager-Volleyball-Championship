<?php


namespace App\Repository;


use App\Entity\Account;

interface AccountRepository
{
    public function getAll(): array;
    public function getById(int $accountId): Account;
    public function save(Account $account): void;
    public function remove(Account $account): void;
}