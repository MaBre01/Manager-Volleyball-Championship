<?php


namespace App\Controller;


use App\Exception\ChampionshipNotFound;
use App\Exception\GameDayNotFound;
use App\Form\EditGameDayDateType;
use App\Repository\ChampionshipRepository;
use App\Repository\GameDayRepository;
use App\Repository\GameRepository;
use App\Utility\CalendarGenerator;
use App\Utility\GameDaysGenerator;
use App\Utility\GamePairGenerator;
use App\Utility\GamesDispatcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameDayController extends AbstractController
{
    /**
     * @Route("championship/{championshipId}/game-day/actual", name="actual_game-day")
     */
    public function actualGameDay(int $championshipId, GameDayRepository $gameDayRepository, ChampionshipRepository $championshipRepository): Response
    {
        try{
            $championship = $championshipRepository->getById( $championshipId );

            /** TODO: mettre number à jour pour la journée actuelle */
            $gameDayActive = $gameDayRepository->getGameDayByNumber($championship, 1);
        }
        catch (ChampionshipNotFound $exception){
            return $this->redirectToRoute("list_championship");
        }
        catch (GameDayNotFound $exception){
            return $this->redirectToRoute("page_championship", [
                "championshipId" => $championship->getId()
            ]);
        }


        return $this->redirectToRoute("page_game-day", [
            "championshipId" => $championship->getId(),
            "gameDayId" => $gameDayActive->getId()
        ]);
    }

    /**
     * @Route("/championship/{championshipId}/game-day/number/{number}", name="number_game-day")
     */
    public function gameDayByNumber(int $championshipId, int $number, GameDayRepository $gameDayRepository, ChampionshipRepository $championshipRepository): Response
    {
        try{
            $championship = $championshipRepository->getById( $championshipId );
            $gameDay = $gameDayRepository->getGameDayByNumber($championship, $number);
        }
        catch (ChampionshipNotFound $exception){
            return $this->redirectToRoute("list_championship");
        }
        catch (GameDayNotFound $exception){
            return $this->redirectToRoute("page_championship", [
                "championshipId" => $championship->getId()
            ]);
        }

        return $this->redirectToRoute("page_game-day", [
            "championshipId" => $championship->getId(),
            "gameDayId" => $gameDay->getId()
        ]);
    }

    /**
     * @Route("championship/{championshipId}/game-day/{gameDayId}", name="page_game-day")
     */
    public function pageGameDay(int $championshipId, int $gameDayId, Request $request, GameDayRepository $gameDayRepository, ChampionshipRepository $championshipRepository): Response
    {
        try{
            $championship = $championshipRepository->getById( $championshipId );
            $gameDay = $gameDayRepository->getById( $gameDayId );
        }
        catch (ChampionshipNotFound $exception){
            return $this->redirectToRoute("list_championship");
        }
        catch (GameDayNotFound $exception){
            return $this->redirectToRoute("page_championship", [
                "championshipId" => $championship->getId()
            ]);
        }

        if( $request->isMethod("POST") ){
            $gameDayIdForm = $request->get("idGameDay");

            return $this->redirectToRoute("page_game-day", [
                "championshipId" => $championship->getId(),
                "gameDayId" => $gameDayIdForm
            ]);
        }

        return $this->render("gameDay/page.html.twig", [
            "championship" => $championship,
            "gameDayActive" => $gameDay
        ]);
    }

    /**
     * @Route("generate-calendar", name="generate_calendar")
     */
    public function generateCalendar(ChampionshipRepository $championshipRepository, GameRepository $gameRepository, GameDayRepository $gameDayRepository): Response
    {
        /** TODO: verifier si les championnats sont commencés */

        $games = $gameRepository->getAll();
        $gameDays = $gameDayRepository->getAll();

        foreach ( $games as $game ){
            $gameRepository->remove( $game );
        }

        foreach ( $gameDays as $gameDay ){
            $gameDayRepository->remove( $gameDay );
        }

        $championships = $championshipRepository->getAll();

        $gamePairs = [];

        foreach ( $championships as $championship ){
            $pairGenerator = new GamePairGenerator($championship->getTeams()->toArray());
            $pairGenerator->generateGamePairs();

            $gamePairs = array_merge($gamePairs, $pairGenerator->getGamePairs());

            $gameDaysGenerator = new GameDaysGenerator( $championship );
            $gameDaysGenerator->generateGameDays();
        }


        $gamesDispatcher = new GamesDispatcher( $gamePairs, $championships );
        $gamesDispatcher->dispatchGame();


        $gameDaysMax = [];
        foreach ( $championships as $championship ){
            $championshipRepository->save( $championship );

            if( count( $gameDaysMax ) < count( $championship->getGameDays() )){
                $gameDaysMax = $championship->getGameDays()->toArray();
            }
        }

        $entity = null;
        $editGameDayForm = $this->createForm(EditGameDayDateType::class, $entity, [
            "gameDays" => $gameDaysMax
        ]);

        /*$calendarGenerator = new CalendarGenerator( $championships );
        $calendarGenerator->generateChampionshipsGames();
        $calendarGenerator->generateGameDays();
        // $calendarGenerator->dispatchGamesInChampionships();

        dump( $calendarGenerator->getGamePairs() );


        return new Response('ok');*/



        return $this->render("championship/generate-calendar.html.twig", [
            "editGameDayDateForm" => $editGameDayForm->createView(),
            "gameDaysMax" => $gameDaysMax
        ]);
    }
}