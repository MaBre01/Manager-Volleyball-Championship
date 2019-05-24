<?php


namespace App\Controller;


use App\Entity\SetPoint;
use App\Exception\ChampionshipNotFound;
use App\Exception\GameDayNotFound;
use App\Exception\GameNotFound;
use App\Exception\SetNotFound;
use App\Form\EditSetPoint;
use App\Form\EditSetPointType;
use App\Repository\ChampionshipRepository;
use App\Repository\GameDayRepository;
use App\Repository\GameRepository;
use App\Repository\SetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SetController extends AbstractController
{
    /**
     * @Route("/championship/{championshipId}/game-day/{gameDayId}/game/{gameId}/set/add", name="add_set")
     * @Route("/championship/{championshipId}/game-day/{gameDayId}/game/{gameId}/set/{setId}/edit", name="edit_set")
     */
    public function addSet(int $championshipId, int $gameDayId, int $gameId, Request $request, ChampionshipRepository $championshipRepository, GameDayRepository $gameDayRepository, GameRepository $gameRepository, SetRepository $setRepository, int $setId = 0): Response
    {
        try{
            $championship = $championshipRepository->getById( $championshipId );
            $gameDay = $gameDayRepository->getById( $gameDayId );
            $game = $gameRepository->getById( $gameId );
            $set = $setRepository->getById( $setId );

            $editSetForm = $this->createForm(EditSetPointType::class, new EditSetPoint($set->getId(), $set->getHomeTeamPoint(), $set->getOutsideTeamPoint(), $set->getGame(), $set->getNumber()) );
            $editMode = true;
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
        catch(SetNotFound $exception){
            $number = count( $game->getSets() ) + 1;

            $editSetForm = $this->createForm(EditSetPointType::class, new EditSetPoint(0, 0, 0, $game, $number) );
            $editMode = false;
        }

        $editSetForm->handleRequest( $request );

        if( $editSetForm->isSubmitted() && $editSetForm->isValid() ){
            $editSet = $editSetForm->getData();

            if( $editMode ){
                $set->changeHomeTeamPoint( $editSet->homeTeamPoint);
                $set->changeOutsideTeamPoint( $editSet->outsideTeamPoint);
            }
            else{
                $set = SetPoint::create( $editSet );
            }

            $setRepository->save( $set );

            return $this->redirectToRoute("page_game", [
                "championshipId" => $championship->getId(),
                "gameDayId" => $gameDay->getId(),
                "gameId" => $game->getId()
            ]);
        }

        return $this->render("set/add.html.twig", [
            "game" => $game,
            "editSetForm" => $editSetForm->createView(),
            "editMode" => $editMode
        ]);
    }

    /**
     * @Route("/championship/{championshipId}/game-day/{gameDayId}/game/{gameId}/set/{setId}/remove", name="remove_set")
     */
    public function remove(int $championshipId, int $gameDayId, int $gameId, int $setId, ChampionshipRepository $championshipRepository, GameDayRepository $gameDayRepository, GameRepository $gameRepository, SetRepository $setRepository): Response
    {
        try{
            $championship = $championshipRepository->getById( $championshipId );
            $gameDay = $gameDayRepository->getById( $gameDayId );
            $game = $gameRepository->getById( $gameId );
            $set = $setRepository->getById( $setId );
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
        catch(SetNotFound $exception){
            return $this->redirectToRoute("page_game", [
                "championshipId" => $championship->getId(),
                "gameDayId" => $gameDay->getId(),
                "game" => $game->getId()
            ]);
        }

        $setRepository->remove( $set );

        return $this->redirectToRoute("page_game", [
            "championshipId" => $championship->getId(),
            "gameDayId" => $gameDay->getId(),
            "gameId" => $game->getId()
        ]);
    }
}