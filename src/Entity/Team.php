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
    private $homeGames;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="outsideTeam")
     */
    private $outsideGames;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Championship", inversedBy="teams")
     */
    private $championship;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $active;

    /**
     * @ORM\OneToOne(targetEntity="Account", mappedBy="team", orphanRemoval=true)
     */
    private $account;

    /**
     * @ORM\Embedded(class="TeamManager")
     */
    private $teamManager;

    public function __construct(int $id, string $name, Club $club, bool $active, TeamManager $teamManager)
    {
        $this->id = $id;
        $this->name = $name;
        $this->point = 0;
        $this->validated = false;
        $this->club = $club;
        $this->games = new ArrayCollection();
        $this->teamManager = $teamManager;
        $this->active = $active;
    }

    public static function create(EditTeam $editTeam): self
    {
        return new self($editTeam->id, $editTeam->name, $editTeam->club, $editTeam->active, new TeamManager($editTeam->managerFirstName, $editTeam->managerLastName, $editTeam->phoneNumber));
    }

    public function edit(EditTeam $editTeam): void
    {
        $this->name = $editTeam->name;
        $this->active = $editTeam->active;
        $this->teamManager->edit($editTeam);
    }

    public function getGamePlayed(): int
    {
        $gamePlayed = 0;

        foreach ( $this->getGames() as $game ){
            if( $game->isFinish() ){
                $gamePlayed++;
            }
        }

        return $gamePlayed;
    }

    public function getGameWon(): int
    {
        $gameWon = 0;

        foreach ($this->getGames() as $game){
            if( $game->isFinish() ){
                if( $game->isTeamWinner( $this ) ){
                    $gameWon++;
                }
            }
        }

        return $gameWon;
    }

    public function getSetWon(): int
    {
        $setWon = 0;

        foreach ($this->getGames() as $game){
            if( $game->isFinish() ){
                foreach ( $game->getSets() as $set ){
                    if( $set->isTeamWinner( $this ) ){
                        $setWon++;
                    };
                }
            }
        }

        return $setWon;
    }

    public function getSetLose(): int
    {
        $setLose = 0;

        foreach ($this->getGames() as $game){
            if( $game->isFinish() ){
                foreach ( $game->getSets() as $set ){
                    if( ! $set->isTeamWinner( $this ) ){
                        $setLose++;
                    };
                }
            }
        }

        return $setLose;
    }

    public function getGameLose(): int
    {
        $gameLose = 0;

        foreach ($this->getGames() as $game){
            if( $game->isFinish() ){
                if( ! $game->isTeamWinner($this) ){
                    $gameLose++;
                }
            }
        }

        return $gameLose;
    }

    public function addPoint(int $point): void
    {
        $this->point += $point;
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

    public function getGames(): Collection
    {
        return new ArrayCollection(
            array_merge($this->homeGames->toArray(), $this->outsideGames->toArray())
        );
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

    public function getAccount(): ?Account
    {
        return $this->account;
    }
}
