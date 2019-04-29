<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DoctrineGameRepository")
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Set", mappedBy="game", orphanRemoval=true)
     */
    private $sets;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GameDay", inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gameDay;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $homeTeam;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $outsideTeam;

    public function __construct(Team $homeTeam, Team $outsideTeam)
    {
        $this->homeTeam = $homeTeam;
        $this->outsideTeam = $outsideTeam;
        $this->sets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Set[]
     */
    public function getSets(): Collection
    {
        return $this->sets;
    }

    public function addSet(Set $set): self
    {
        if (!$this->sets->contains($set)) {
            $this->sets[] = $set;
            $set->setGame($this);
        }

        return $this;
    }

    public function removeSet(Set $set): self
    {
        if ($this->sets->contains($set)) {
            $this->sets->removeElement($set);
            // set the owning side to null (unless already changed)
            if ($set->getGame() === $this) {
                $set->setGame(null);
            }
        }

        return $this;
    }

    public function getGameDay(): ?GameDay
    {
        return $this->gameDay;
    }

    public function setGameDay(?GameDay $gameDay): self
    {
        $this->gameDay = $gameDay;

        return $this;
    }

    public function getHomeTeam(): ?Team
    {
        return $this->homeTeam;
    }

    public function getOutsideTeam(): ?Team
    {
        return $this->outsideTeam;
    }
}
