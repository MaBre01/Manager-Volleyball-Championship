<?php

namespace App\Entity;

use App\Form\EditClub;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DoctrineClubRepository")
 */
class Club
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
     * @ORM\OneToMany(targetEntity="App\Entity\Pitch", mappedBy="club", orphanRemoval=true, cascade={"persist"})
     */
    private $pitches;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Team", mappedBy="club", orphanRemoval=true, cascade={"persist"})
     */
    private $teams;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;

        $this->pitches = new ArrayCollection();
        $this->teams = new ArrayCollection();
    }

    public static function register(EditClub $editClub): self
    {
        return new self($editClub->id, $editClub->name);
    }

    public function rename(string $name): void
    {
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Collection|Pitch[]
     */
    public function getPitches(): Collection
    {
        return $this->pitches;
    }

    public function addPitch(Pitch $pitch): self
    {
        if (!$this->pitches->contains($pitch)) {
            $this->pitches[] = $pitch;
            $pitch->setClub($this);
        }

        return $this;
    }

    public function removePitch(Pitch $pitch): self
    {
        if ($this->pitches->contains($pitch)) {
            $this->pitches->removeElement($pitch);
            // set the owning side to null (unless already changed)
            if ($pitch->getClub() === $this) {
                $pitch->setClub(null);
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
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->contains($team)) {
            $this->teams->removeElement($team);
            // set the owning side to null (unless already changed)
            if ($team->getClub() === $this) {
                $team->setClub(null);
            }
        }

        return $this;
    }
}
