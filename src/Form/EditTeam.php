<?php


namespace App\Form;


use App\Entity\Club;

class EditTeam
{
    public $id;
    public $name;
    public $club;

    public function __construct(int $id, ?string $name, Club $club)
    {
        $this->id = $id;
        $this->name = $name;
        $this->club = $club;
    }
}