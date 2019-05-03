<?php


namespace App\Form;


use App\Entity\Club;

class EditTeam
{
    public $id;
    public $name;
    public $club;
    public $managerFirstName;
    public $managerLastName;
    public $phoneNumber;
    public $email;
    public $password;
    public $roles = [];
    public $active;

    public function __construct(int $id, ?string $name, Club $club, String $managerFirstName, String $managerLastName, String $phoneNumber, String $email, String $password, array $roles, bool $active)
    {
        $this->id = $id;
        $this->name = $name;
        $this->club = $club;
        $this->managerFirstName = $managerFirstName;
        $this->managerLastName = $managerLastName;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
        $this->password = $password;
        $this->roles = $roles;
    }
}