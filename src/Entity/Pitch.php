<?php

namespace App\Entity;

use App\Form\EditPitch;
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Gap", inversedBy="pitches", cascade={"persist"})
     */
    private $gaps;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Club", inversedBy="pitches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $club;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Team", mappedBy="pitches")
     */
    private $teams;

    public function __construct(string $address, array $gaps)
    {
        $this->address = $address;
        $this->gaps = $gaps;
    }

    public static function create(EditPitch $editPitch, array $gaps): self
    {
        $gapsChecked = [];
        foreach ($gaps as $gap) {
            if ($editPitch->monday && $gap->getId() === 1) {
                $gapsChecked[] = $gap;
            }
            if ($editPitch->tuesday && $gap->getId() === 2) {
                $gapsChecked[] = $gap;
            }
            if ($editPitch->wednesday && $gap->getId() === 3) {
                $gapsChecked[] = $gap;
            }
            if ($editPitch->thursday && $gap->getId() === 4) {
                $gapsChecked[] = $gap;
            }
            if ($editPitch->friday && $gap->getId() === 5) {
                $gapsChecked[] = $gap;
            }
            if ($editPitch->saturday && $gap->getId() === 6) {
                $gapsChecked[] = $gap;
            }
            if ($editPitch->sunday && $gap->getId() === 7) {
                $gapsChecked[] = $gap;
            }
        }
        return new self($editPitch->address, $gapsChecked);
    }

    public function edit(EditPitch $editPitch, array $gaps) {
        $gapsChecked = [];
        foreach ($gaps as $gap) {
            if ($editPitch->monday && $gap->getId() === 1) {
                $gapsChecked[] = $gap;
            }
            if ($editPitch->tuesday && $gap->getId() === 2) {
                $gapsChecked[] = $gap;
            }
            if ($editPitch->wednesday && $gap->getId() === 3) {
                $gapsChecked[] = $gap;
            }
            if ($editPitch->thursday && $gap->getId() === 4) {
                $gapsChecked[] = $gap;
            }
            if ($editPitch->friday && $gap->getId() === 5) {
                $gapsChecked[] = $gap;
            }
            if ($editPitch->saturday && $gap->getId() === 6) {
                $gapsChecked[] = $gap;
            }
            if ($editPitch->sunday && $gap->getId() === 7) {
                $gapsChecked[] = $gap;
            }
        }
        $this->gaps = $gapsChecked;
        $this->address = $editPitch->address;
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

    public function setClub($club): void
    {
        $this->club = $club;
    }

    public function getTeams()
    {
        return $this->teams;
    }
}
