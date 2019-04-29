<?php


namespace App\Exception;


use Throwable;

class GameDayNotFound extends \DomainException
{
    public function __construct(int $gameDayId)
    {
        parent::__construct("GameDay with id '" . $gameDayId . "' not found");
    }
}