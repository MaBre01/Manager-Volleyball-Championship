<?php

namespace App\Entity;

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

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->began = false;
        $this->gameDays = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBegan(): bool
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
}
