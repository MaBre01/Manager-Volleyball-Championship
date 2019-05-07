<?php


namespace App\Exception;


use App\Entity\Team;

class NotInSameChampionship extends \UnexpectedValueException
{
    public function __construct(Team $homeTeam, Team $outsideTeam)
    {
        parent::__construct($homeTeam->getName() . " and " . $outsideTeam->getName() . " are not in the same championship");
    }
}