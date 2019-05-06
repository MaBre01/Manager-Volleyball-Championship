<?php

namespace App\Entity;

use App\Form\EditTeam;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DoctrineTeamRepository")
 */
class Team
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
     * @ORM\Column(type="integer")
     */
    private $point;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validated;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Club", inversedBy="teams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $club;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="homeTeam")
     */
    private $games;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Championship", inversedBy="teams")
     */
    private $championship;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $active;

    /**
     * @ORM\OneToOne(targetEntity="Account", inversedBy="team")
     */
    private $account;
    /**
     * @ORM\Embedded(class="TeamManager")
     */
    private $teamManager;

    public function __construct(int $id, string $name, Club $club, bool $active, Account $account, TeamManager $teamManager)
    {
        $this->id = $id;
        $this->name = $name;
        $this->point = 0;
        $this->validated = false;
        $this->club = $club;
        $this->games = new ArrayCollection();
        $this->account = $account;
        $this->teamManager = $teamManager;
        $this->active = $active;
    }

    public static function create(EditTeam $editTeam): self
    {
        return new self($editTeam->id, $editTeam->name, $editTeam->club, $editTeam->active, new Account($editTeam->email, $editTeam->password, $editTeam->roles), new TeamManager($editTeam->managerFirstName, $editTeam->managerLastName, $editTeam->phoneNumber));
    }

    public function rename(string $name): void
    {
        $this->name = $name;
    }

    public function unlinkChampionship(): void
    {
        $this->championship = null;
    }

    public function linkChampionship(Championship $championship): void
    {
        $this->championship = $championship;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPoint(): ?int
    {
        return $this->point;
    }

    public function isValidated(): ?bool
    {
        return $this->validated;
    }

    public function getClub(): ?Club
    {
        return $this->club;
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
            $game->setHomeTeam($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            // set the owning side to null (unless already changed)
            if ($game->getHomeTeam() === $this) {
                $game->setHomeTeam(null);
            }
        }

        return $this;
    }

    public function getChampionship(): ?Championship
    {
        return $this->championship;
    }

    public function getTeamManager(): TeamManager
    {
        return $this->teamManager;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
