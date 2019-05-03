<?php


namespace App\Exception;


use Throwable;

class AccountNotFound extends \DomainException
{
    public function __construct(int $accountId)
    {
        parent::__construct("Account with id '" . $accountId . "' not found");
    }
}