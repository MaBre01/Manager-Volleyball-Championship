<?php


namespace App\Form;


use App\Entity\Game;

class EditSetPoint
{
    public $id;
    public $homeTeamPoint;
    public $outsideTeamPoint;
    public $game;
    public $number;

    public function __construct(int $id, int $homeTeamPoint, int $outsideTeamPoint, Game $game, int $number)
    {
        $this->id = $id;
        $this->homeTeamPoint = $homeTeamPoint;
        $this->outsideTeamPoint = $outsideTeamPoint;
        $this->game = $game;
        $this->number = $number;
    }
}