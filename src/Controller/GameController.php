<?php


namespace App\Controller;


use App\Entity\SetPoint;
use App\Exception\ChampionshipNotFound;
use App\Exception\GameDayNotFound;
use App\Exception\GameNotFound;
use App\Repository\ChampionshipRepository;
use App\Repository\GameDayRepository;
use App\Repository\GameRepository;
use App\Repository\SetRepository;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    /**
     * @Route("championship/{championshipId}/game-day/{gameDayId}/game/{gameId}", name="page_game")
     */
    public function pageGame(int $championshipId, int $gameDayId, int $gameId, ChampionshipRepository $championshipRepository, GameDayRepository $gameDayRepository, GameRepository $gameRepository): Response
    {
        try{
            $championship = $championshipRepository->getById( $championshipId );
            $gameDay = $gameDayRepository->getById( $gameDayId );
            $game = $gameRepository->getById( $gameId );
        }
        catch (ChampionshipNotFound $exception){
            return $this->redirectToRoute("list_championship");
        }
        catch (GameDayNotFound $exception){
            return $this->redirectToRoute("page_championship", [
                "championshipId" => $championship->getId()
            ]);
        }
        catch(GameNotFound $exception){
            return $this->redirectToRoute("page_game-day", [
                "championshipId" => $championship->getId(),
                "gameDayId" => $gameDay->getId()
            ]);
        }

        return $this->render('game/page.html.twig', [
            "championship" => $championship,
            "game" => $game
        ]);
    }

    /**
     * @Route("championship/{championshipId}/game-day/{gameDayId}/game/{gameId}/forfeit", name="forfeit_game")
     */
    public function forfeit(int $championshipId, int $gameDayId, int $gameId, Request $request, ChampionshipRepository $championshipRepository, GameDayRepository $gameDayRepository, GameRepository $gameRepository, SetRepository $setRepository): Response
    {
        try{
            $championship = $championshipRepository->getById( $championshipId );
            $gameDay = $gameDayRepository->getById( $gameDayId );
            $game = $gameRepository->getById( $gameId );
        }
        catch (ChampionshipNotFound $exception){
            return $this->redirectToRoute("list_championship");
        }
        catch (GameDayNotFound $exception){
            return $this->redirectToRoute("page_championship", [
                "championshipId" => $championship->getId()
            ]);
        }
        catch(GameNotFound $exception){
            return $this->redirectToRoute("page_game-day", [
                "championshipId" => $championship->getId(),
                "gameDayId" => $gameDay->getId()
            ]);
        }

        $teamForfeit = "NONE";
        if( $game->isHomeTeamWinner() === null ){
            $teamForfeit = "NONE";
        }
        elseif ( $game->isHomeTeamWinner() && $game->isForfeit() ){
            $teamForfeit = "OUTSIDETEAM";
        }
        elseif ( ! $game->isHomeTeamWinner() && $game->isForfeit() ){
            $teamForfeit = "HOMETEAM";
        }

        if( $request->isMethod( "POST" ) ){
            $teamForfeit = $request->get("teamForfeit");

            if( $teamForfeit === "HOMETEAM" ){

                $game->getSets()->clear();

                for ( $i = 1; $i <= 3; $i++ ){
                    $set = new SetPoint(0,0,1, $i, $game);
                    $setRepository->save( $set );
                }

                $game->forfeit();

            }
            elseif( $teamForfeit === "OUTSIDETEAM" ){

                $game->getSets()->clear();

                for ( $i = 1; $i <= 3; $i++ ){
                    $set = new SetPoint(0,1,0, $i, $game);
                    $setRepository->save( $set );
                }

                $game->forfeit();
            }
            else{
                $game->unForfeit();
            }

            $gameRepository->save( $game );

            return $this->redirectToRoute("page_game", [
                "championshipId" => $championship->getId(),
                "gameDayId" => $gameDay->getId(),
                "gameId" => $game->getId()
            ]);
        }


        return $this->render("game/forfeit.html.twig", [
            "game" => $game,
            "teamForfeit" => $teamForfeit
        ]);
    }

    /**
     * @Route("championship/{championshipId}/game-day/{gameDayId}/game/{gameId}/finish", name="finish_game")
     */
    public function finishGame(int $championshipId, int $gameDayId, int $gameId, ChampionshipRepository $championshipRepository, GameDayRepository $gameDayRepository, GameRepository $gameRepository, TeamRepository $teamRepository): Response
    {
        try{
            $championship = $championshipRepository->getById( $championshipId );
            $gameDay = $gameDayRepository->getById( $gameDayId );
            $game = $gameRepository->getById( $gameId );
        }
        catch (ChampionshipNotFound $exception){
            return $this->redirectToRoute("list_championship");
        }
        catch (GameDayNotFound $exception){
            return $this->redirectToRoute("page_championship", [
                "championshipId" => $championship->getId()
            ]);
        }
        catch(GameNotFound $exception){
            return $this->redirectToRoute("page_game-day", [
                "championshipId" => $championship->getId(),
                "gameDayId" => $gameDay->getId()
            ]);
        }


        if( ! $game->isFinish() ){

            $homeTeam = $game->getHomeTeam();
            $outsideTeam = $game->getOutsideTeam();

            if( $game->isForfeit() ){
                if( $game->isHomeTeamWinner() ){
                    $homeTeam->addPoint( $championship->getSpecificationPoint()->getWinWithBonusPoint() );
                    $outsideTeam->addPoint( $championship->getSpecificationPoint()->getForfeitPoint() );
                }
                else{
                    $homeTeam->addPoint( $championship->getSpecificationPoint()->getForfeitPoint() );
                    $outsideTeam->addPoint( $championship->getSpecificationPoint()->getWinWithBonusPoint() );
                }
            }
            else{
                if( $game->isHomeTeamWinner() && $game->getOutsideTeamScore() < 2 ){
                    $homeTeam->addPoint( $championship->getSpecificationPoint()->getWinWithBonusPoint() );
                    $outsideTeam->addPoint( $championship->getSpecificationPoint()->getLoosePoint() );
                }
                elseif ( $game->isHomeTeamWinner() && $game->getOutsideTeamScore() === 2 ){
                    $homeTeam->addPoint( $championship->getSpecificationPoint()->getWinPoint() );
                    $outsideTeam->addPoint( $championship->getSpecificationPoint()->getLooseWithBonusPoint() );
                }
                elseif ( ! $game->isHomeTeamWinner() && $game->getHomeTeamScore() < 2 ){
                    $homeTeam->addPoint( $championship->getSpecificationPoint()->getLoosePoint() );
                    $outsideTeam->addPoint( $championship->getSpecificationPoint()->getWinWithBonusPoint() );
                }
                elseif ( ! $game->isHomeTeamWinner() && $game->getHomeTeamScore() === 2 ){
                    $homeTeam->addPoint( $championship->getSpecificationPoint()->getLooseWithBonusPoint() );
                    $outsideTeam->addPoint( $championship->getSpecificationPoint()->getWinPoint() );
                }
            }

            $teamRepository->save( $homeTeam );
            $teamRepository->save( $outsideTeam );

            $game->finish();

            $gameRepository->save( $game );
        }

        return $this->redirectToRoute("page_game",[
            "championshipId" => $championship->getId(),
            "gameDayId" => $gameDay->getId(),
            "gameId" => $game->getId()
        ]);
    }
}