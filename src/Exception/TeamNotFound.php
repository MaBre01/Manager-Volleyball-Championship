<?php


namespace App\Exception;


use Throwable;

class TeamNotFound extends \DomainException
{
    public function __construct(int $teamId)
    {
        parent::__construct("Team with id '" . $teamId . "' not found");
    }
}