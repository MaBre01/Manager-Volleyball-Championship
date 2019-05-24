<?php

namespace App\Entity;

use App\Form\EditSetPoint;
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

    public function __construct(int $id, int $homeTeamPoint, int $outsideTeamPoint, int $number, Game $game)
    {
        $this->id = $id;
        $this->homeTeamPoint = $homeTeamPoint;
        $this->outsideTeamPoint = $outsideTeamPoint;
        $this->game = $game;
        $this->number = $number;
    }

    public static function create(EditSetPoint $editSetPoint): self
    {
        return new self($editSetPoint->id, $editSetPoint->homeTeamPoint, $editSetPoint->outsideTeamPoint, $editSetPoint->number, $editSetPoint->game);
    }

    public function isHomeTeamWinner(): bool
    {
        if( $this->homeTeamPoint > $this->outsideTeamPoint ){
            return true;
        }

        return false;
    }

    public function isTeamWinner(Team $team): bool
    {
        if( $team == $this->game->getHomeTeam() ){
            return $this->isHomeTeamWinner();
        }
        elseif( $team == $this->game->getOutsideTeam() ){
            return ! $this->isHomeTeamWinner();
        }

        return null;
    }

    public function changeHomeTeamPoint(int $homeTeamPoint): void
    {
        $this->homeTeamPoint = $homeTeamPoint;
    }

    public function changeOutsideTeamPoint(int $outsideTeamPoint): void
    {
        $this->outsideTeamPoint = $outsideTeamPoint;
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
