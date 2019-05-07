<?php


namespace App\Controller;


use App\Entity\Championship;
use App\Entity\SpecificationPoint;
use App\Exception\ChampionshipNotFound;
use App\Exception\TeamNotFound;
use App\Form\EditChampionship;
use App\Form\EditChampionshipSpecificationPoint;
use App\Form\EditChampionshipSpecificationPointType;
use App\Form\EditChampionshipType;
use App\Form\EditParticipatingTeam;
use App\Form\EditParticipatingTeamType;
use App\Repository\ChampionshipRepository;
use App\Repository\GameDayRepository;
use App\Repository\GameRepository;
use App\Repository\TeamRepository;
use App\Utility\GameDaysGenerator;
use App\Utility\GamePairGenerator;
use App\Utility\GamesDispatcher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChampionshipController extends AbstractController
{
    /**
     * @Route("/championship", name="list_championship")
     */
    public function listChampionship(ChampionshipRepository $championshipRepository): Response
    {
        return $this->render('championship/list.html.twig', [
            "championships" => $championshipRepository->getAll()
        ]);
    }

    /**
     * @Route("/championship/new", name="add_championship")
     * @Route("/championship/{championshipId}/edit", name="edit_championship")
     */
    public function editChampionship(Request $request, ChampionshipRepository $championshipRepository, int $championshipId = 0): Response
    {
        try{
            $championship = $championshipRepository->getById( $championshipId );
            $editForm = $this->createForm(
                EditChampionshipType::class,
                new EditChampionship($championship->getId(), $championship->getName())
            );
            $editMode = true;
        }
        catch(ChampionshipNotFound $exception){
            $editForm = $this->createForm(
                EditChampionshipType::class,
                new EditChampionship(0, null)
            );

            $editMode = false;
        }

        $editForm->handleRequest( $request );

        if( $editForm->isSubmitted() && $editForm->isValid() ){
            $editChampionship = $editForm->getData();

            if( $editMode ){
                $championship->rename( $editChampionship->name );
            }
            else{
                $championship = Championship::create($editChampionship);
            }

            $championshipRepository->save($championship);

            return $this->redirectToRoute('page_championship', [
               'championshipId' => $championship->getId()
            ]);
        }

        return $this->render('championship/edit.html.twig', [
            "editChampionshipForm" => $editForm->createView(),
            "editMode" => $editMode
        ]);
    }

    /**
     * @Route("/championship/{championshipId}", name="page_championship")
     */
    public function pageChampionship(int $championshipId, ChampionshipRepository $championshipRepository): Response
    {
        try{
            $championship = $championshipRepository->getById( $championshipId );
        }
        catch(ChampionshipNotFound $exception){
            return $this->redirectToRoute('list_championship');
        }


        if( $championship->isBegan() ){
            return $this->render('championship/began-information.html.twig', [
                'championship' => $championship
            ]);
        }
        else{
            return $this->render('championship/unbegan.html.twig',[
                'championship' => $championship
            ]);
        }
    }

    /**
     * @Route("championship/{championshipId}/ranking", name="ranking_championship")
     */
    public function rankings(int $championshipId, ChampionshipRepository $championshipRepository): Response
    {
        try{
            $championship = $championshipRepository->getById( $championshipId );
        }
        catch(ChampionshipNotFound $exception){
            return $this->redirectToRoute("list_championship");
        }

        if( ! $championship->isBegan() ){
            return $this->redirectToRoute("page_championship", [
                "championshipId" => $championship->getId()
            ]);
        }

        return $this->render("championship/ranking.html.twig", [
            "championship" => $championship
        ]);
    }

    /**
     * @Route("championship/{championshipId}/results", name="results_championship")
     */
    public function results(int $championshipId, Request $request, ChampionshipRepository $championshipRepository): Response
    {
        try{
            $championship = $championshipRepository->getById( $championshipId );
        }
        catch (ChampionshipNotFound $exception){
            return $this->redirectToRoute("list_championship");
        }

        $gameDayActiveId = (int) $request->get("gameDay", 0);

        if( $gameDayActiveId ){
            foreach ( $championship->getGameDays() as $gameDay ){
                if( $gameDay->getId() === $gameDayActiveId ){
                    $gameDayActive = $gameDay;
                }
            }
        }
        else{
            $gameDayActive = $championship->getGameDays()->current();
        }


        return $this->render("championship/results.html.twig", [
           "championship" => $championship,
            "gameDayActive" => $gameDayActive
        ]);
    }

    /**
     * @Route("/championship/{championshipId}/specification-point", name="edit_specification_point_championship")
     */
    public function updateSpecificationPoint(int $championshipId, Request $request, ChampionshipRepository $championshipRepository): Response
    {
        try{
            $championship = $championshipRepository->getById( $championshipId );
        }
        catch(ChampionshipNotFound $exception){
            return $this->redirectToRoute('list_championship');
        }

        if( $championship->isBegan() ){
            return $this->redirectToRoute('page_championship', [
                "championshipId" => $championship->getId()
            ]);
        }

        $editForm = $this->createForm(
            EditChampionshipSpecificationPointType::class,
            new EditChampionshipSpecificationPoint(
                $championship->getSpecificationPoint()->getWinPoint(),
                $championship->getSpecificationPoint()->getWinWithBonusPoint(),
                $championship->getSpecificationPoint()->getLoosePoint(),
                $championship->getSpecificationPoint()->getLooseWithBonusPoint(),
                $championship->getSpecificationPoint()->getForfeitPoint()
            )
        );

        $editForm->handleRequest( $request );

        if( $editForm->isSubmitted() && $editForm->isValid() ){
            $editSpecificationPoint = $editForm->getData();

            $specificationPoint = SpecificationPoint::create( $editSpecificationPoint );
            $championship->updateSpecificationPoint( $specificationPoint );

            $championshipRepository->save( $championship );

            return $this->redirectToRoute('page_championship', [
                "championshipId" => $championship->getId()
            ]);
        }

        return $this->render('championship/edit-specification-point.html.twig', [
            "editSpecificationPointForm" => $editForm->createView(),
            "championship" => $championship
        ]);
    }

    /**
     * @Route("/championship/{championshipId}/team", name="edit_participating-team_championship")
     */
    public function addParticipatingTeam(int $championshipId, Request $request, ChampionshipRepository $championshipRepository, TeamRepository $teamRepository): Response
    {
        try{
            $championship = $championshipRepository->getById( $championshipId );
        }
        catch(ChampionshipNotFound $exception){
            return $this->redirectToRoute('list_championship');
        }

        if( $championship->isBegan() ){
            return $this->redirectToRoute('page_championship', [
                "championshipId" => $championship->getId()
            ]);
        }

        $participatingTeamForm = $this->createForm(
            EditParticipatingTeamType::class,
            new EditParticipatingTeam(null),
            ['teams' => $teamRepository->getAllWithoutChampionship()]);
        $participatingTeamForm->handleRequest( $request );

        if( $participatingTeamForm->isSubmitted() && $participatingTeamForm->isValid() ){
            $editParticipatingTeam = $participatingTeamForm->getData();


            /* TODO: try catch */
            $partipatingTeam = $teamRepository->getById( $editParticipatingTeam->team->getId() );



            $championship->addTeam( $partipatingTeam );
            $championshipRepository->save( $championship );

            return $this->redirectToRoute('edit_participating-team_championship', [
                "championshipId" => $championship->getId()
            ]);
        }

        return $this->render('championship/edit-participating-team.html.twig', [
            "editParticipatingTeamForm" => $participatingTeamForm->createView(),
            "championship" => $championship
        ]);
    }

    /**
     * @Route("/championship/{championshipId}/team/{teamId}/remove", name="remove_participating_team_championship")
     */
    public function removeParticipatingTeam(int $championshipId, int $teamId, ChampionshipRepository $championshipRepository, TeamRepository $teamRepository): Response
    {
        try{
            $championship = $championshipRepository->getById( $championshipId );
            $team = $teamRepository->getById( $teamId );
        }
        catch(ChampionshipNotFound $exception){
            return $this->redirectToRoute("list_championship");
        }
        catch(TeamNotFound $exception){
            return $this->redirectToRoute( "edit_participating-team_championship", [
                "championshipId" => $championship->getId()
            ]);
        }

        if( $championship->isBegan() ){
            return $this->redirectToRoute('page_championship', [
                "championshipId" => $championship->getId()
            ]);
        }

        $championship->removeTeam( $team );
        $championshipRepository->save( $championship );

        return $this->redirectToRoute( "edit_participating-team_championship", [
            "championshipId" => $championship->getId()
        ]);
    }

    /**
     * @Route("/championship/{championshipId}/beginning", name="beginning_championship")
     */
    public function beginningChampionship(int $championshipId, ChampionshipRepository $championshipRepository): Response
    {
        try{
            $championship = $championshipRepository->getById( $championshipId );
        }
        catch( ChampionshipNotFound $exception ){
            return $this->redirectToRoute("list_championship");
        }

        $championship->begin();
        $championshipRepository->save( $championship );

        return $this->redirectToRoute("page_championship", [
            "championshipId" => $championship->getId()
        ]);
    }

    /**
     * @Route("/test", name="test_championships")
     */
    public function test(ChampionshipRepository $championshipRepository, GameRepository $gameRepository, GameDayRepository $gameDayRepository): Response
    {
//        $games = $gameRepository->getAll();
//        $gameDays = $gameDayRepository->getAll();
//
//        foreach ( $games as $game ){
//            $gameRepository->remove( $game );
//        }
//
//        foreach ( $gameDays as $gameDay ){
//            $gameDayRepository->remove( $gameDay );
//        }

        $championships = $championshipRepository->getAll();

        $gamePairs = [];

        foreach ( $championships as $championship ){
            $pairGenerator = new GamePairGenerator($championship->getTeams()->toArray());
            $pairGenerator->generateGamePairs();

            $gamePairs = array_merge($gamePairs, $pairGenerator->getGamePairs());

            $gameDaysGenerator = new GameDaysGenerator( $championship );
            $gameDaysGenerator->generateGameDays();
        }

        dump( "gameday and gamepair generate");

        $gamesDispatcher = new GamesDispatcher( $gamePairs, $championships );
        $gamesDispatcher->dispatchGame();

        dump("dispatch game done");

        foreach ( $championships as $championship ){
            $championshipRepository->save( $championship );
        }

        return new Response("finish");
    }
}