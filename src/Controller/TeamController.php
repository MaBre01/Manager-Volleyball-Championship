<?php


namespace App\Controller;


use App\Exception\TeamNotFound;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    /**
     * @Route("/team/{teamId}", name="page_team")
     */
    public function pageTeam(int $teamId, TeamRepository $teamRepository): Response
    {
        try{
            $team = $teamRepository->getById( $teamId );
        }
        catch (TeamNotFound $exception){
            return $this->redirectToRoute('list_team');
        }

        return $this->render('team/page.html.twig', [
            'team' => $team
        ]);
    }

    public function listTeam(TeamRepository $teamRepository): Response
    {
        return $this->render('team/list.html.twig',[
            'teams' => $teamRepository->getAll()
        ]);
    }
}