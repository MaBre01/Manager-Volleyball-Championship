<?php

namespace App\Entity;

use App\Form\EditChampionship;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DoctrineChampionshipRepository")
 */
class Championship
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $began;

    /**
     * @ORM\Embedded(class="SpecificationPoint")
     */
    private $specificationPoint;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GameDay", mappedBy="championship", orphanRemoval=true)
     */
    private $gameDays;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Team", mappedBy="championship")
     */
    private $teams;

    public function __construct(string $name, SpecificationPoint $specificationPoint)
    {
        $this->name = $name;
        $this->began = false;
        $this->specificationPoint = $specificationPoint;

        $this->gameDays = new ArrayCollection();
        $this->teams = new ArrayCollection();
    }

    public static function create(EditChampionship $editChampionship): self
    {
        return new self($editChampionship->name, new SpecificationPoint(2,3,0,1,-1));
    }

    public function rename(string $name): void
    {
        $this->name = $name;
    }

    public function updateSpecificationPoint(SpecificationPoint $specificationPoint): void
    {
        $this->specificationPoint = $specificationPoint;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isBegan(): bool
    {
        return $this->began;
    }

    public function getSpecificationPoint(): SpecificationPoint
    {
        return $this->specificationPoint;
    }

    /**
     * @return Collection|GameDay[]
     */
    public function getGameDays(): Collection
    {
        return $this->gameDays;
    }

    public function addGameDay(GameDay $gameDay): self
    {
        if (!$this->gameDays->contains($gameDay)) {
            $this->gameDays[] = $gameDay;
            $gameDay->setChampionship($this);
        }

        return $this;
    }

    public function removeGameDay(GameDay $gameDay): self
    {
        if ($this->gameDays->contains($gameDay)) {
            $this->gameDays->removeElement($gameDay);
            // set the owning side to null (unless already changed)
            if ($gameDay->getChampionship() === $this) {
                $gameDay->setChampionship(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->linkChampionship( $this );
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->contains($team)) {
            $this->teams->removeElement($team);
            $team->unlinkChampionship();
        }

        return $this;
    }
}
