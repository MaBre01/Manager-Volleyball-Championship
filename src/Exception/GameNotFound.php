<?php


namespace App\Exception;


use Throwable;

class GameNotFound extends \DomainException
{
    public function __construct(int $gameId)
    {
        parent::__construct("Game with id '" . $gameId . "' not found");
    }
}