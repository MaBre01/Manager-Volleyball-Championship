<?php
/**
 * Created by PhpStorm.
 * User: flore
 * Date: 18/03/2019
 * Time: 13:34
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DoctrineAccountRepository")
 */
class Account implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\Column(type="json")
     */
    protected $roles = [];

    /**
     * @ORM\OneToOne(targetEntity="Team", inversedBy="account")
     */
    private $team;

    public function __construct(String $email, String $password, array $roles, $team)
    {
        $this->email = $email;
        $this->password = $password;
        $this->roles = $roles;
        $this->team = $team;
    }

    public function setAccount($email): void {
        $this->email = $email;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): String
    {
        return $this->email;
    }

    public function getPassword(): String
    {
        return $this->password;
    }
    
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function setRoles($roles): void {
        $this->roles = $roles;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function getSalt() {}

    public function getUsername() {
        return $this->getEmail();
    }

    public function eraseCredentials() {}
}