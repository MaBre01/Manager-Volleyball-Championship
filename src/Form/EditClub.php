<?php


namespace App\Form;


class EditClub
{
    public $id;
    public $name;

    public function __construct(int $id, ?string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}