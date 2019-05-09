<?php


namespace App\Entity;

use App\Form\EditTeam;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class TeamManager
{
    /**
     * @ORM\Column(type="string")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string")
     */
    private $phoneNumber;

    public function __construct(String $firstName, String $lastName, String $phoneNumber)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phoneNumber = $phoneNumber;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getPhoneNumber(): String
    {
        return $this->phoneNumber;
    }

    public function edit(EditTeam $editTeam): void
    {
        $this->firstName = $editTeam->managerFirstName;
        $this->lastName = $editTeam->managerLastName;
        $this->phoneNumber = $editTeam->phoneNumber;
    }

}