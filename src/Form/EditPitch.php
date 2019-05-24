<?php


namespace App\Form;


use App\Entity\Club;

class EditPitch
{
    public $id;
    public $address;
    public $monday;
    public $tuesday;
    public $wednesday;
    public $thursday;
    public $friday;
    public $saturday;
    public $sunday;
    public $club;

    public function __construct(int $id, ?string $address, ?bool $monday, ?bool $tuesday, ?bool $wednesday, ?bool $thursday, ?bool $friday, ?bool $saturday, ?bool $sunday, Club $club)
    {
        $this->id = $id;
        $this->address = $address;
        $this->monday = $monday;
        $this->tuesday = $tuesday;
        $this->wednesday = $wednesday;
        $this->thursday = $thursday;
        $this->friday = $friday;
        $this->saturday = $saturday;
        $this->sunday = $sunday;
        $this->club = $club;
    }
}