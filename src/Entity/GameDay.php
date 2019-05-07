<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DoctrineGameDayRepository")
 */
class GameDay
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $end;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\Column(type="boolean")
     */
    private $phase;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="gameDay", orphanRemoval=true, cascade={"persist"})
     */
    private $games;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Championship", inversedBy="gameDays")
     * @ORM\JoinColumn(nullable=false)
     */
    private $championship;

    public function __construct(int $number, bool $phase, Championship $championship)
    {
        $this->number = $number;
        $this->phase = $phase;
        $this->championship = $championship;
        $this->games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getPhase(): bool
    {
        return $this->phase;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            // set the owning side to null (unless already changed)
            if ($game->getGameDay() === $this) {
                $game->setGameDay(null);
            }
        }

        return $this;
    }

    public function getChampionship(): ?Championship
    {
        return $this->championship;
    }

    public function setChampionship(?Championship $championship): self
    {
        $this->championship = $championship;

        return $this;
    }
}
