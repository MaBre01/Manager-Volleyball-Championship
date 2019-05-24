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
     * @ORM\OneToMany(targetEntity="App\Entity\SetPoint", mappedBy="game", orphanRemoval=true)
     */
    private $sets;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GameDay", inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gameDay;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="homeGames")
     * @ORM\JoinColumn(nullable=false)
     */
    private $homeTeam;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="outsideGames")
     * @ORM\JoinColumn(nullable=false)
     */
    private $outsideTeam;

    /**
     * @ORM\Column(type="boolean")
     */
    private $finish;

    /**
     * @ORM\Column(type="boolean")
     */
    private $forfeit;

    public function __construct(Team $homeTeam, Team $outsideTeam, GameDay $gameDay, bool $forfeit = false)
    {
        $this->homeTeam = $homeTeam;
        $this->outsideTeam = $outsideTeam;
        $this->gameDay = $gameDay;
        $this->forfeit = $forfeit;

        $this->sets = new ArrayCollection();
    }

    public function finish(): void
    {
        $this->finish = true;
    }

    public function forfeit(): void
    {
        $this->forfeit = true;
    }

    public function unForfeit(): void
    {
        $this->forfeit = false;
    }

    public function isTeamWinner(Team $team): bool
    {
        if( $team == $this->homeTeam ){
            return $this->isHomeTeamWinner();
        }
        elseif( $team == $this->outsideTeam ){
            return ! $this->isHomeTeamWinner();
        }

        return null;
    }

    public function isHomeTeamWinner(): ?bool
    {
        if( $this->getHomeTeamScore() > $this->getOutsideTeamScore() ){
            return true;
        }
        elseif ($this->getHomeTeamScore() < $this->getOutsideTeamScore() ){
            return false;
        }

        return null;
    }

    public function getHomeTeamScore(): int
    {
        $homeTeamScore = 0;

        foreach ( $this->sets as $set ){
            if( $set->isHomeTeamWinner() ){
                $homeTeamScore++;
            }
        }

        return $homeTeamScore;
    }

    public function getOutsideTeamScore(): int
    {
        $outsideTeamScore = 0;

        foreach ( $this->sets as $set ){
            if( ! $set->isHomeTeamWinner() ){
                $outsideTeamScore++;
            }
        }

        return $outsideTeamScore;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|SetPoint[]
     */
    public function getSets(): Collection
    {
        return $this->sets;
    }

    public function addSet(SetPoint $set): self
    {
        if (!$this->sets->contains($set)) {
            $this->sets[] = $set;
            $set->setGame($this);
        }

        return $this;
    }

    public function removeSet(SetPoint $set): self
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

    public function isFinish(): bool
    {
        return $this->finish;
    }

    public function isForfeit(): bool
    {
        return $this->forfeit;
    }
}
