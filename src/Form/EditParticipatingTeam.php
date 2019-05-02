<?php


namespace App\Form;


use App\Entity\Team;

class EditParticipatingTeam
{
    public $team;

    public function __construct(?Team $team)
    {
        $this->team = $team;
    }
}