<?php


namespace App\Utility;


use App\Entity\Club;
use App\Entity\Game;
use App\Entity\GameDay;

class GamesDispatcher
{
    private $gamePairs;
    private $championships;

    public function __construct(array $gamePairs, array $championships)
    {
        $this->gamePairs = $gamePairs;
        $this->championships = $championships;
    }

    public function getGamePairs(): array
    {
        return $this->gamePairs;
    }

    public function sortOnWeight(): void
    {
        $indexWeight = [];
        foreach ( $this->gamePairs as $index => $gamePair){
            $indexWeight[ $index ] = $gamePair["weight"];
        }

        array_multisort( $indexWeight, SORT_DESC, $this->gamePairs );
    }

    public function dispatchGame(): void
    {
        $copyGamePairs = $this->gamePairs;

        while( count($this->gamePairs) > 0 ){
            $this->gamePairs = $copyGamePairs;
            shuffle( $this->gamePairs );
            $this->initializeGameDays();

            foreach ($this->gamePairs as $index => $gamePair){
                $championshipActive = $gamePair["championship"];
                $gameDaysActive = $championshipActive->getGameDays();
                $gamePlaced = false;


                foreach ( $gameDaysActive as $gameDay ){

                    if( ! $gamePlaced ){

                        if( ! $this->checkIfTeamAlreadyPlay($gameDay->getGames()->toArray(), [ $gamePair["homeTeam"], $gamePair["outsideTeam"] ]) ){

                            if( ! $this->checkIfClubHaveAlreadyTwoHomeTeamGames($gamePair["homeTeam"]->getClub(), $gameDay->getNumber() ) ){

                                if( ! $this->checkIfSameChampionshipClubTeamPlayHome($gamePair["homeTeam"]->getClub(), $gameDay) ){
                                    $game = new Game( $gamePair["homeTeam"], $gamePair["outsideTeam"], $gameDay);
                                    $gameDay->addGame( $game );

                                    unset($this->gamePairs[ $index ]);
                                    $gamePlaced = true;
                                }

                            }

                        }
                    }
                }
            }
        }
    }

    private function checkIfTeamAlreadyPlay(array $games, array $teams): bool
    {
        foreach ( $games as $game ){
            foreach ($teams as $team){
                if( $game->getHomeTeam()->getId() === $team->getId() ){
                    return true;
                }

                if( $game->getOutsideTeam()->getId() === $team->getId() )
                {
                    return true;
                }
            }
        }

        return false;
    }

    private function checkIfClubHaveAlreadyTwoHomeTeamGames(Club $club, int $day): bool
    {
        $numberOfHomeGames = 0;
        foreach ( $this->championships as $championship ){
            foreach ( $championship->getGameDays() as $gameDay ){
                if( $gameDay->getNumber() === $day ){

                    foreach ($gameDay->getGames() as $game){

                        if( $game->getHomeTeam()->getClub()->getId() === $club->getId() ){
                            $numberOfHomeGames++;
                        }

                    }

                }
            }
        }

        if( $numberOfHomeGames >= 2 ){
            return true;
        }
        else{
            return false;
        }
    }

    private function checkIfSameChampionshipClubTeamPlayHome(Club $club, GameDay $gameDay): bool
    {
        foreach ($gameDay->getGames() as $game ){
            if( $game->getHomeTeam()->getClub()->getId() === $club->getId() ){
                return true;
            }
        }

        return false;
    }

    private function initializeGameDays(): void
    {
        foreach ( $this->championships as $championship ){
            foreach ( $championship->getGameDays() as $gameDay ){
                foreach ( $gameDay->getGames() as $index => $game){
                    $gameDay->getGames()->remove( $index );
                }
            }
        }
    }
}