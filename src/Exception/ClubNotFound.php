<?php


namespace App\Exception;


use Throwable;

class ClubNotFound extends \DomainException
{
    public function __construct(int $clubId)
    {
        parent::__construct("Club with id '" . $clubId . "' not found");
    }
}