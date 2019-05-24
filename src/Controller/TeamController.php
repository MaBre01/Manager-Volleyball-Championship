<?php


namespace App\Controller;


use App\Entity\Pitch;
use App\Exception\TeamNotFound;
use App\Form\EditTeam;
use App\Form\EditTeamType;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    /**
     * @Route("/team/{teamId}", name="page_team")
     */
    public function pageTeam(Request $request, int $teamId, TeamRepository $teamRepository): Response
    {
        try{
            $team = $teamRepository->getById( $teamId );
        }
        catch (TeamNotFound $exception){
            return $this->redirectToRoute('list_team');
        }

        $editForm = $this->createForm(
            EditTeamType::class,
            new EditTeam($team->getId(), $team->getName(), $team->getClub(),
                $team->getTeamManager()->getFirstName(),
                $team->getTeamManager()->getLastName(),
                $team->getTeamManager()->getPhoneNumber(),
                $team->getAccount()->getEmail(),
                $team->getAccount()->getPassword(),
                $team->getAccount()->getRoles(),
                $team->isActive())
        );
        $editForm->handleRequest($request);

        if( $editForm->isSubmitted() && $editForm->isValid() ){
            $editTeam = $editForm->getData();

            $pitches = [];
            $pitchesId = $request->get('pitches');
            if ($pitchesId != null) {
                foreach ($pitchesId as $pitchId) {
                    $pitch = $this->getDoctrine()->getRepository(Pitch::class)->find($pitchId);
                    $pitches[] = $pitch;
                }
            }
            $team->edit($editTeam, $pitches);

            $teamRepository->save($team);

            return $this->redirectToRoute('list_team_club', [
                "clubId" => $team->getClub()->getId()
            ]);
        }

        return $this->render('team/page.html.twig', [
            'editTeamForm' => $editForm->createView(),
            'club' => $team->getClub(),
            'team' => $team
        ]);
    }

    /**
     * @Route("/team", name="list_team")
     */
    public function listTeam(TeamRepository $teamRepository): Response
    {
        return $this->render('team/list.html.twig',[
            'teams' => $teamRepository->getAll()
        ]);
    }

    /**
     * @Route("/team/{teamId}/remove", name="remove_team")
     */
    public function removeTeam(int $teamId, TeamRepository $teamRepository): Response
    {
        try{
            $team = $teamRepository->getById( $teamId );
        }
        catch(TeamNotFound $exception){
            return $this->redirectToRoute('list_team');
        }

        $teamRepository->remove( $team );

        return $this->redirectToRoute('list_team');
    }

    /**
     * @Route("/team/{teamId}/edit", name="edit_team")
     */
    public function editTeam(int $teamId, Request $request, TeamRepository $teamRepository): Response
    {
        try{
            $team = $teamRepository->getById( $teamId );
        }
        catch (TeamNotFound $exception){
            return $this->redirectToRoute('list_team');
        }

        $editForm = $this->createForm(
            EditTeamType::class,
            new EditTeam($team->getId(), $team->getName(), $team->getClub(),
                        $team->getTeamManager()->getFirstName(),
                        $team->getTeamManager()->getLastName(),
                        $team->getTeamManager()->getPhoneNumber(),
                        $team->getAccount()->getEmail(),
                        $team->getAccount()->getPassword(),
                        $team->getAccount()->getRoles(),
                        $team->isActive())
        );
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $editTeam = $editForm->getData();

            $team->edit($editTeam);

            $teamRepository->save($team);

            return $this->redirectToRoute('page_team', [
                "teamId" => $team->getId()
            ]);
        }

        return $this->render('team/edit.html.twig', [
            "editTeamForm" => $editForm->createView(),
            "team" => $team
        ]);
    }
}