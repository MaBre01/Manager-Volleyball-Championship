<?php


namespace App\Controller;


use App\Entity\Club;
use App\Entity\Team;
use App\Exception\ClubNotFound;
use App\Form\EditClub;
use App\Form\EditClubType;
use App\Form\EditTeam;
use App\Form\EditTeamType;
use App\Repository\ClubRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ClubController extends AbstractController
{
    /**
     * @Route("/club", name="list_club")
     */
    public function listClub(ClubRepository $clubRepository): Response
    {
        return $this->render('club/list.html.twig', [
            "clubs" => $clubRepository->getAll()
        ]);
    }

    /**
     * @Route("/club/{clubId}/edit", name="edit_club")
     * @Route("/club/new", name="add_club")
     */
    public function editClub(Request $request, ClubRepository $clubRepository, int $clubId = 0): Response
    {
        try{
            $club = $clubRepository->getById( $clubId );
            $editForm = $this->createForm(
                EditClubType::class,
                new EditClub($club->getId(), $club->getName())
            );

            $editMode = true;
        }
        catch (ClubNotFound $exception){
            $editForm = $this->createForm(
                EditClubType::class,
                new EditClub(0, null));

            $editMode = false;
        }

        $editForm->handleRequest( $request );

        if( $editForm->isSubmitted() && $editForm->isValid() ){
            $editClub = $editForm->getData();

            if( $editMode ){
                $club->rename( $editClub->name );
            }
            else{
                $club = Club::register( $editClub );
            }

            $clubRepository->save( $club );

            return $this->redirectToRoute('page_club', ["clubId" => $club->getId()]);
        }

        return $this->render('club/edit.html.twig', [
            'editClubForm' => $editForm->createView(),
            'editMode' => $editMode
        ]);
    }

    /**
     * @Route("/club/{clubId}", name="page_club")
     */
    public function pageClub(int $clubId, ClubRepository $clubRepository): Response
    {
        try{
            $club = $clubRepository->getById( $clubId );
        }
        catch (ClubNotFound $exception){
            return $this->redirectToRoute('list_club');
        }

        return $this->render('club/page.html.twig', [
            'club' => $club
        ]);
    }

    /**
     * @Route("/club/{clubId}/remove", name="remove_club")
     */
    public function removeClub(int $clubId, ClubRepository $clubRepository): Response
    {
        try{
            $club = $clubRepository->getById( $clubId );
            $clubRepository->remove( $club );
        }
        catch(ClubNotFound $exception){
            return $this->redirectToRoute('list_club');
        }

        return $this->redirectToRoute('list_club');
    }

    /**
     * @Route("/club/{clubId}/team", name="list_team_club")
     */
    public function listTeam(int $clubId, ClubRepository $clubRepository): Response
    {
        try{
            $club = $clubRepository->getById( $clubId );
        }
        catch(ClubNotFound $exception){
            return $this->redirectToRoute('list_club');
        }

        return $this->render('club/list-team.html.twig', [
            'club' => $club
        ]);
    }

    /**
     * @Route("/club/{clubId}/team/new", name="add_team_club")
     */
    public function addTeam(int $clubId, Request $request, ClubRepository $clubRepository): Response
    {
        try{
            $club = $clubRepository->getById( $clubId );
        }
        catch(ClubNotFound $exception){
            return $this->redirectToRoute('list_club');
        }

        $addForm = $this->createForm(EditTeamType::class, new EditTeam(0, null, $club));
        $addForm->handleRequest($request);

        if( $addForm->isSubmitted() && $addForm->isValid() ){
            $editTeam = $addForm->getData();

            $team = Team::create( $editTeam );
            $club->addTeam( $team );

            $clubRepository->save( $club );

            return $this->redirectToRoute('list_team_club', [
                "clubId" => $club->getId()
            ]);
        }

        return $this->render('club/add-team.html.twig',[
            'addTeamForm' => $addForm->createView()
        ]);
    }
}