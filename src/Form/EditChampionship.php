<?php


namespace App\Form;


class EditChampionship
{
    public $id;
    public $name;

    public function __construct(int $id, ?string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}