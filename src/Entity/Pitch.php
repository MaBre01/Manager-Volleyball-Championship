<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DoctrinePitchRepository")
 */
class Pitch
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
    private $address;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Gap")
     */
    private $gaps;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Club", inversedBy="pitches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $club;

    public function __construct(string $address)
    {
        $this->address = $address;
        $this->gaps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @return Collection|Gap[]
     */
    public function getGaps(): Collection
    {
        return $this->gaps;
    }

    public function addGap(Gap $gap): self
    {
        if (!$this->gaps->contains($gap)) {
            $this->gaps[] = $gap;
        }

        return $this;
    }

    public function removeGap(Gap $gap): self
    {
        if ($this->gaps->contains($gap)) {
            $this->gaps->removeElement($gap);
        }

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }
}
