<?php


namespace App\Exception;


use Throwable;

class ChampionshipNotFound extends \DomainException
{
    public function __construct(int $championshipId)
    {
        parent::__construct("Championship with id '" . $championshipId . "' not found");
    }
}