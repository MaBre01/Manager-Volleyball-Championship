<?php


namespace App\Controller;


use App\Entity\Account;
use App\Entity\Club;
use App\Entity\Pitch;
use App\Entity\Team;
use App\Exception\ClubNotFound;
use App\Exception\PitchNotFound;
use App\Form\EditClub;
use App\Form\EditClubType;
use App\Form\EditPitch;
use App\Form\EditPitchType;
use App\Form\EditTeam;
use App\Form\EditTeamType;
use App\Repository\AccountRepository;
use App\Repository\ClubRepository;
use App\Repository\GapRepository;
use App\Repository\PitchRepository;
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
            'clubs' => $clubRepository->getAll()
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

            return $this->redirectToRoute('page_club', ['clubId' => $club->getId()]);
        }

        return $this->render('club/edit.html.twig', [
            'editClubForm' => $editForm->createView(),
            'editMode' => $editMode
        ]);
    }

    /**
     * @Route("/club/{clubId}", name="page_club")
     */
    public function pageClub(Request $request, int $clubId, ClubRepository $clubRepository): Response
    {
        try{
            $club = $clubRepository->getById( $clubId );
            $editForm = $this->createForm(
                EditClubType::class,
                new EditClub($club->getId(), $club->getName())
            );

        }
        catch (ClubNotFound $exception){
            return $this->redirectToRoute('list_club');
        }

        $editForm->handleRequest( $request );

        if( $editForm->isSubmitted() && $editForm->isValid() ) {
            $editClub = $editForm->getData();

            $club->rename($editClub->name);

            $clubRepository->save( $club );
        }

        return $this->render('club/page.html.twig', [
            'editClubForm' => $editForm->createView(),
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
    public function addTeam(Request $request, int $clubId, ClubRepository $clubRepository, AccountRepository $accountRepository, \Swift_Mailer $mailer): Response
    {
        try{
            $club = $clubRepository->getById( $clubId );
        }
        catch(ClubNotFound $exception){
            return $this->redirectToRoute('list_club');
        }

        $addForm = $this->createForm(EditTeamType::class, new EditTeam(0, null, $club, null,
                                                                null, null, null, null,
                                                                          null, null));
        $addForm->handleRequest($request);

        if( $addForm->isSubmitted() && $addForm->isValid() ){
            $editTeam = $addForm->getData();

            $pitches = [];
            $pitchesId = $request->get('pitches');
            if ($pitchesId != null) {
                foreach ($pitchesId as $pitchId) {
                    $pitch = $this->getDoctrine()->getRepository(Pitch::class)->find($pitchId);
                    $pitches[] = $pitch;
                }
            }
            $team = Team::create($editTeam, $pitches);

            $tmpPassword = random_int(10000,999999);

            $message = (new \Swift_Message('Changement de mot de passe'))
                ->setFrom('volleyballchampionshipmanager@gmail.com', 'Volleyball CM')
                ->setTo($editTeam->email)
                ->setBody($tmpPassword)
            ;

            $mailer->send($message);

            $password = password_hash($tmpPassword, PASSWORD_BCRYPT);

            $account = new Account($editTeam->email, $password, ["ROLE_TEAM"], $team);
            $accountRepository->save($account);
            $club->addTeam( $team );

            $clubRepository->save( $club );

            return $this->redirectToRoute('list_team_club', [
                'clubId' => $club->getId()
            ]);
        }

        return $this->render('club/add-team.html.twig',[
            'addTeamForm' => $addForm->createView(),
            'club' => $club
        ]);
    }

    /**
     * @Route("/club/{clubId}/pitch", name="list_pitch_club")
     */
    public function listPitchClub(int $clubId, ClubRepository $clubRepository): Response
    {
        try{
            $club = $clubRepository->getById( $clubId );
        }
        catch(ClubNotFound $exception){
            return $this->redirectToRoute('list_club');
        }

        return $this->render('club/list-pitch.html.twig', [
            "club" => $club
        ]);
    }

    /**
     * @Route("/club/{clubId}/pitch/add", name="add_pitch_club")
     */
    public function addPitchClub(Request $request, int $clubId, ClubRepository $clubRepository, GapRepository $gapRepository): Response
    {
        try{
            $club = $clubRepository->getById( $clubId );
        }
        catch (ClubNotFound $exception){
            return $this->redirectToRoute("list_pitch_club");
        }

        $addForm = $this->createForm(EditPitchType::class, new EditPitch(0, null, null, null,
                                                                     null, null, null, null,
                                                                        null, $club));
        $addForm->handleRequest($request);

        if ($addForm->isSubmitted() && $addForm->isValid()) {
            $editPitch = $addForm->getData();

            $gaps = $gapRepository->getAll();
            $pitch = Pitch::create($editPitch, $gaps);

            $club->addPitch($pitch);

            $clubRepository->save($club);

            return $this->redirectToRoute('list_pitch_club', [
                'clubId' => $club->getId()
            ]);
        }

        return $this->render('club/add-pitch.html.twig',[
            'addPitchForm' => $addForm->createView()
        ]);
    }

    /**
     * @Route("/pitch/{pitchId}", name="page_pitch")
     */
    public function pagePitch(Request $request, int $pitchId, PitchRepository $pitchRepository, GapRepository $gapRepository): Response
    {
        try{
            $pitch = $pitchRepository->getById( $pitchId );
        }
        catch (PitchNotFound $exception){
            return $this->redirectToRoute('list_pitch_club');
        }

        $gaps = [false,false,false,false,false,false,false];
        foreach ($pitch->getGaps() as $gap) {
            $gaps[$gap->getId() - 1] = true;
        }

        $editForm = $this->createForm(
            EditPitchType::class,
            new EditPitch($pitch->getId(), $pitch->getAddress(),
                $gaps[0],
                $gaps[1],
                $gaps[2],
                $gaps[3],
                $gaps[4],
                $gaps[5],
                $gaps[6],
                $pitch->getClub())
        );
        $editForm->handleRequest( $request );

        if( $editForm->isSubmitted() && $editForm->isValid() ){
            $editPitch = $editForm->getData();

            $gaps = $gapRepository->getAll();
            $pitch->edit($editPitch, $gaps);

            $pitchRepository->save( $pitch );

            return $this->redirectToRoute('list_pitch_club', [
                'clubId' => $pitch->getClub()->getId()
            ]);
        }

        return $this->render('pitch/page.html.twig', [
            'editPitchForm' => $editForm->createView(),
            'pitch' => $pitch
        ]);
    }

    /**
     * @Route("/pitch/{pitchId}/remove", name="remove_pitch")
     */
    public function removePitch(int $pitchId, PitchRepository $pitchRepository): Response
    {
        try{
            $pitch = $pitchRepository->getById($pitchId);
        }
        catch(PitchNotFound $exception){
            return $this->redirectToRoute('list_pitch_club');
        }

        $pitchRepository->remove($pitch);

        return $this->redirectToRoute('list_pitch_club', [
            "clubId" => $pitch->getClub()->getId()
        ]);
    }
}