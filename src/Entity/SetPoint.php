<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DoctrineSetRepository")
 */
class SetPoint
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $homeTeamPoint;

    /**
     * @ORM\Column(type="integer")
     */
    private $outsideTeamPoint;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Game", inversedBy="sets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    public function __construct(int $homeTeamPoint, int $outsideTeamPoint, int $number)
    {
        $this->homeTeamPoint = $homeTeamPoint;
        $this->outsideTeamPoint = $outsideTeamPoint;
        $this->number = $number;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHomeTeamPoint(): int
    {
        return $this->homeTeamPoint;
    }

    public function getOutsideTeamPoint(): int
    {
        return $this->outsideTeamPoint;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }
}
