<?php


namespace App\Utility;


use App\Exception\NotInSameChampionship;

class GamePairGenerator
{
    private $teams;
    private $gamePairs;

    public function __construct(array $teams)
    {
        $this->teams = $teams;
        $this->gamePairs = [];
    }

    public function getGamePairs(): array
    {
        return $this->gamePairs;
    }

    public function generateGamePairs(): void
    {
        $teamGames = $this->initializeTeamGames();
        $numberOfGamePerTeam = count( $this->teams ) - 1;

        foreach ($this->teams as $homeTeam){
            foreach ($this->teams as $outsideTeam){

                if( ! ($homeTeam->getId() === $outsideTeam->getId()) ){
                    if( $homeTeam->getChampionship() != $outsideTeam->getChampionship() ){
                        throw new NotInSameChampionship($homeTeam, $outsideTeam);
                    }
                    else{
                        $championship = $homeTeam->getChampionship();
                    }

                    $pair = [
                        "homeTeam" => $homeTeam,
                        "outsideTeam" => $outsideTeam,
                        "championship" => $championship
                    ];

                    $inversedPair = [
                        "homeTeam" => $outsideTeam,
                        "outsideTeam" => $homeTeam,
                        "championship" => $championship
                    ];

                    if( ! in_array($pair, $this->gamePairs) ){
                        if( ! in_array($inversedPair, $this->gamePairs) ){
                            if( $teamGames[ $pair["homeTeam"]->getId() ] < round($numberOfGamePerTeam / 2) ){
                                $this->gamePairs[] = $pair;
                                $teamGames[ $pair["homeTeam"]->getId() ]++;
                            }
                            else{
                                $this->gamePairs[] = $inversedPair;
                                $teamGames[ $inversedPair["homeTeam"]->getId() ]++;
                            }
                        }
                    }
                }
            }
        }
    }

    public function weightGamePairs(): void
    {
        $gamePairs = [];

        foreach ($this->gamePairs as $gamePair){
            $weight = 0;

            $nbTeamClubHomeTeam = count( $gamePair["homeTeam"]->getClub()->getTeams() );

            $nbTeamClubOutsideTeam = count( $gamePair["outsideTeam"]->getClub()->getTeams() );

            $weight = $nbTeamClubHomeTeam + $nbTeamClubOutsideTeam;

            $gamePair["weight"] = $weight;

            $gamePairs[] = $gamePair;
        }

        $this->gamePairs = $gamePairs;
    }

    private function initializeTeamGames(): array
    {
        $teamGames = [];

        foreach ($this->teams as $team){
            $teamGames[ $team->getId() ] = 0;
        }

        return $teamGames;
    }
}